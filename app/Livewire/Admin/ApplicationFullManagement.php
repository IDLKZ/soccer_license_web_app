<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Models\ApplicationStatusCategory;
use App\Models\Club;
use App\Models\Licence;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class ApplicationFullManagement extends Component
{
    use WithPagination;

    // Search & Filters
    public $search = '';

    public $filterStatus = '';

    public $filterLicence = '';

    public $filterClub = '';

    // Permissions
    #[Locked]
    public $canView = false;

    #[Locked]
    public $canManage = false;

    #[Locked]
    public $canDelete = false;

    // Delete Modal
    public $showDeleteModal = false;

    public $deleteId = null;

    public function mount()
    {
        $this->authorize('view-full-application');

        $user = Auth::user();
        $this->canView = $user->can('view-full-application');
        $this->canManage = $user->can('manage-full-application');
        $this->canDelete = $user->can('delete-full-application');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterLicence()
    {
        $this->resetPage();
    }

    public function updatedFilterClub()
    {
        $this->resetPage();
    }

    public function openDeleteModal($id)
    {
        $application = Application::find($id);

        if (! $application) {
            session()->flash('error', 'Заявка не найдена.');

            return;
        }

        // Check if application can be deleted (license_id or club_id is null)
        if ($application->license_id !== null && $application->club_id !== null) {
            session()->flash('error', 'Нельзя удалить заявку с назначенной лицензией и клубом.');

            return;
        }

        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function deleteApplication()
    {
        $this->authorize('delete-full-application');

        try {
            $application = Application::find($this->deleteId);

            if (! $application) {
                session()->flash('error', 'Заявка не найдена.');
                $this->closeDeleteModal();

                return;
            }

            // Final check: can only delete if license_id or club_id is null
            if ($application->license_id !== null && $application->club_id !== null) {
                session()->flash('error', 'Нельзя удалить заявку с назначенной лицензией и клубом.');
                $this->closeDeleteModal();

                return;
            }

            $application->delete();

            session()->flash('success', 'Заявка успешно удалена.');
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при удалении заявки: '.$e->getMessage());
            $this->closeDeleteModal();
        }
    }

    public function viewDetails($id)
    {
        return redirect()->route('admin.application-detailed', ['application_id' => $id]);
    }

    public function render()
    {
        $applications = Application::with([
            'application_status_category',
            'licence.season',
            'licence.league',
            'club',
            'user',
        ])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('id', 'like', '%'.$this->search.'%')
                        ->orWhereHas('club', function ($clubQuery) {
                            $clubQuery->where('short_name_ru', 'like', '%'.$this->search.'%')
                                ->orWhere('short_name_kk', 'like', '%'.$this->search.'%')
                                ->orWhere('short_name_en', 'like', '%'.$this->search.'%')
                                ->orWhere('full_name_ru', 'like', '%'.$this->search.'%')
                                ->orWhere('full_name_kk', 'like', '%'.$this->search.'%')
                                ->orWhere('full_name_en', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('user', function ($userQuery) {
                            $userQuery->where('first_name', 'like', '%'.$this->search.'%')
                                ->orWhere('last_name', 'like', '%'.$this->search.'%')
                                ->orWhere('email', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('category_id', $this->filterStatus);
            })
            ->when($this->filterLicence, function ($query) {
                $query->where('license_id', $this->filterLicence);
            })
            ->when($this->filterClub, function ($query) {
                $query->where('club_id', $this->filterClub);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $statuses = ApplicationStatusCategory::all();
        $licences = Licence::with('season', 'league')->get();
        $clubs = Club::orderBy('short_name_ru')->get();

        return view('livewire.admin.application-full-management', [
            'applications' => $applications,
            'statuses' => $statuses,
            'licences' => $licences,
            'clubs' => $clubs,
        ])->layout(get_user_layout());
    }
}
