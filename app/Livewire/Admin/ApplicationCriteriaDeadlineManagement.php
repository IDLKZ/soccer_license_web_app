<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Models\ApplicationCriteriaDeadline;
use App\Models\ApplicationCriterion;
use App\Models\ApplicationStatus;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Управление дедлайнами критериев заявок')]
class ApplicationCriteriaDeadlineManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal States
    public $showEditModal = false;

    public $editingDeadlineId = null;

    // Search & Filters
    public $search = '';

    public $filterApplication = '';

    public $filterStatus = '';

    // Form Data
    #[Validate('required|integer|exists:applications,id')]
    public $applicationId = '';

    #[Validate('required|integer|exists:application_criteria,id')]
    public $applicationCriteriaId = '';

    #[Validate('nullable|date')]
    public $deadlineStartAt = null;

    #[Validate('required|date|after_or_equal:deadlineStartAt')]
    public $deadlineEndAt = '';

    #[Validate('required|integer|exists:application_statuses,id')]
    public $statusId = '';

    // Relationships Data
    public $applications = [];

    public $applicationStatuses = [];

    public $criteriaByApplication = [];

    // Permissions
    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        // Authorization
        $this->authorize('view-application-criteria-deadline');

        // Set permissions
        $user = auth()->user();
        $this->canEdit = $user ? $user->can('manage-application-criteria-deadline') : false;
        $this->canDelete = $user ? $user->can('delete-application-criteria-deadline') : false;

        // Load relationship data
        $this->loadRelationships();
    }

    public function loadRelationships()
    {
        $this->applications = Application::with(['licence', 'club'])
            ->orderBy('created_at', 'desc')
            ->get();

        $this->applicationStatuses = ApplicationStatus::whereIn('value', [
            'first-check-revision',
            'industry-check-revision',
            'control-check-revision',
        ])->get();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterApplication()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedApplicationId()
    {
        if ($this->applicationId) {
            $this->criteriaByApplication = ApplicationCriterion::with('category_document')
                ->where('application_id', $this->applicationId)
                ->get();
        } else {
            $this->criteriaByApplication = [];
        }
    }

    public function getDeadlines()
    {
        $query = ApplicationCriteriaDeadline::with([
            'application.licence',
            'application.club',
            'application_criterion.category_document',
            'application_status',
        ]);

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('application.club', function ($cq) {
                    $cq->where('short_name_ru', 'like', '%'.$this->search.'%')
                        ->orWhere('short_name_kk', 'like', '%'.$this->search.'%')
                        ->orWhere('full_name_ru', 'like', '%'.$this->search.'%');
                })
                    ->orWhereHas('application.licence', function ($lq) {
                        $lq->where('title_ru', 'like', '%'.$this->search.'%')
                            ->orWhere('title_kk', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('application_criterion.category_document', function ($cdq) {
                        $cdq->where('title_ru', 'like', '%'.$this->search.'%')
                            ->orWhere('title_kk', 'like', '%'.$this->search.'%');
                    });
            });
        }

        // Filter by application
        if (! empty($this->filterApplication)) {
            $query->where('application_id', $this->filterApplication);
        }

        // Filter by status
        if (! empty($this->filterStatus)) {
            $query->where('status_id', $this->filterStatus);
        }

        return $query->orderBy('deadline_end_at', 'desc')->paginate(20);
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function editDeadline($deadlineId)
    {
        $deadline = ApplicationCriteriaDeadline::findOrFail($deadlineId);
        $this->authorize('manage-application-criteria-deadline');

        $this->editingDeadlineId = $deadline->id;
        $this->applicationId = $deadline->application_id;
        $this->applicationCriteriaId = $deadline->application_criteria_id;
        $this->deadlineStartAt = $deadline->deadline_start_at ? $deadline->deadline_start_at->format('Y-m-d\TH:i') : null;
        $this->deadlineEndAt = $deadline->deadline_end_at->format('Y-m-d\TH:i');
        $this->statusId = $deadline->status_id;

        // Load criteria for selected application
        $this->updatedApplicationId();

        $this->showEditModal = true;
    }

    public function updateDeadline()
    {
        $this->authorize('manage-application-criteria-deadline');

        $deadline = ApplicationCriteriaDeadline::findOrFail($this->editingDeadlineId);

        $this->validate();

        $deadline->update([
            'application_id' => $this->applicationId,
            'application_criteria_id' => $this->applicationCriteriaId,
            'deadline_start_at' => $this->deadlineStartAt,
            'deadline_end_at' => $this->deadlineEndAt,
            'status_id' => $this->statusId,
        ]);

        $this->resetForm();
        toastr()->success('Дедлайн успешно обновлен');
    }

    public function deleteDeadline($deadlineId)
    {
        $this->authorize('delete-application-criteria-deadline');

        $deadline = ApplicationCriteriaDeadline::findOrFail($deadlineId);
        $deadline->delete();

        toastr()->success('Дедлайн успешно удален');
    }

    public function closeEditModal()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->showEditModal = false;
        $this->reset([
            'editingDeadlineId',
            'applicationId',
            'applicationCriteriaId',
            'deadlineStartAt',
            'deadlineEndAt',
            'statusId',
            'criteriaByApplication',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.application-criteria-deadline-management', [
            'deadlines' => $this->getDeadlines(),
        ])->layout(get_user_layout());
    }
}
