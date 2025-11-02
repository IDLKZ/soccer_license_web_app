<?php

namespace App\Livewire\Club;

use App\Models\Licence;
use App\Models\CategoryDocument;
use App\Models\ClubTeam;
use App\Models\Club;
use App\Models\Application;
use App\Models\ApplicationCriterion;
use App\Models\ApplicationStatus;
use App\Models\ApplicationStatusCategory;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SingleLicenceDetail extends Component
{
    #[Locked]
    public $licenceId;

    public $licence;
    public $activeCategory = null;
    public $categories = [];
    public $requirementsByCategory = [];

    // Application submission
    public $showApplicationModal = false;
    public $canApply = false;
    public $myClubs = [];
    public $availableClubs = [];

    #[Validate('required|exists:clubs,id')]
    public $selectedClubId = null;

    public function mount($id)
    {
        $this->licenceId = $id;
        $this->loadLicence();
        $this->loadRequirements();
        $this->checkCanApply();
    }

    public function loadLicence()
    {
        $this->licence = Licence::with(['season', 'league', 'licence_deadlines.club'])
            ->findOrFail($this->licenceId);
    }

    public function loadRequirements()
    {
        // Get all requirements for this licence with their relationships
        $requirements = $this->licence->licence_requirements()
            ->with(['category_document', 'document'])
            ->get();

        // Group requirements by category
        $grouped = $requirements->groupBy('category_id');

        // Load category details
        $this->categories = CategoryDocument::whereIn('id', $grouped->keys())
            ->orderBy('title_ru')
            ->get();

        // Organize requirements by category
        foreach ($grouped as $categoryId => $categoryRequirements) {
            $this->requirementsByCategory[$categoryId] = $categoryRequirements;
        }

        // Set first category as active
        if ($this->categories->count() > 0 && !$this->activeCategory) {
            $this->activeCategory = $this->categories->first()->id;
        }
    }

    public function switchCategory($categoryId)
    {
        $this->activeCategory = $categoryId;
    }

    public function checkCanApply()
    {
        // Check permission
        $user = auth()->user();
        if (!$user || !$user->can('apply-for-license')) {
            $this->canApply = false;
            return;
        }

        // Get user's clubs
        $this->myClubs = ClubTeam::where('user_id', $user->id)
            ->pluck('club_id')
            ->toArray();

        if (empty($this->myClubs)) {
            $this->canApply = false;
            return;
        }

        // Check for active applications (category.value != 'rejected')
        $rejectedCategoryValue = 'rejected';
        $existingApplications = Application::where('license_id', $this->licenceId)
            ->whereIn('club_id', $this->myClubs)
            ->whereHas('application_status_category', function($query) use ($rejectedCategoryValue) {
                $query->where('value', '!=', $rejectedCategoryValue);
            })
            ->exists();

        if ($existingApplications) {
            $this->canApply = false;
            return;
        }

        $this->canApply = true;
    }

    public function openApplicationModal()
    {
        if (!$this->canApply) {
            session()->flash('error', 'У вас нет прав на подачу заявки или заявка уже существует.');
            return;
        }

        // Get available clubs based on licence_deadlines
        $licenceDeadlines = $this->licence->licence_deadlines()
            ->whereIn('club_id', $this->myClubs)
            ->pluck('club_id')
            ->toArray();

        // If there are deadlines, show only those clubs
        // Otherwise show all user's clubs
        $clubIds = !empty($licenceDeadlines) ? $licenceDeadlines : $this->myClubs;

        $this->availableClubs = Club::whereIn('id', $clubIds)->get();

        if ($this->availableClubs->count() === 0) {
            session()->flash('error', 'У вас нет доступных клубов для подачи заявки.');
            return;
        }

        // Auto-select if only one club
        if ($this->availableClubs->count() === 1) {
            $this->selectedClubId = $this->availableClubs->first()->id;
        }

        $this->showApplicationModal = true;
    }

    public function closeApplicationModal()
    {
        $this->showApplicationModal = false;
        $this->selectedClubId = null;
        $this->resetValidation();
    }

    public function submitApplication()
    {
        $this->validate();

        // Double check permission
        if (!auth()->user()->can('apply-for-license')) {
            session()->flash('error', 'У вас нет прав на подачу заявки.');
            $this->closeApplicationModal();
            return;
        }

        try {
            // Get first category
            $firstCategory = ApplicationStatusCategory::where('is_first', true)->first();
            if (!$firstCategory) {
                session()->flash('error', 'Ошибка: не найдена начальная категория статуса.');
                return;
            }

            // Get deadline for selected club if exists
            $deadline = $this->licence->licence_deadlines()
                ->where('club_id', $this->selectedClubId)
                ->first();

            // Create application
            $application = Application::create([
                'user_id' => auth()->id(),
                'license_id' => $this->licenceId,
                'club_id' => $this->selectedClubId,
                'category_id' => $firstCategory->id,
                'is_active' => true,
                'is_ready' => false,
                'deadline' => $deadline ? $deadline->end_at : null,
            ]);

            // Get first status
            $firstStatus = ApplicationStatus::where('is_first', true)->first();
            if (!$firstStatus) {
                session()->flash('error', 'Ошибка: не найден начальный статус.');
                $application->delete();
                return;
            }

            // Get unique category_ids from licence_requirements
            $uniqueCategories = $this->licence->licence_requirements()
                ->select('category_id')
                ->distinct()
                ->pluck('category_id')
                ->toArray();

            // Create application_criteria for each unique category
            foreach ($uniqueCategories as $categoryId) {
                ApplicationCriterion::create([
                    'application_id' => $application->id,
                    'category_id' => $categoryId,
                    'status_id' => $firstStatus->id,
                    'is_ready' => false,
                ]);
            }

            session()->flash('success', 'Заявка успешно подана!');
            $this->closeApplicationModal();

            // Refresh data
            $this->checkCanApply();

        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при подаче заявки: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.club.single-licence-detail')->layout(get_user_layout());
    }

    public function getTitle()
    {
        return $this->licence ? $this->licence->title_ru : 'Детали лицензии';
    }
}
