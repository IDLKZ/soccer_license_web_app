<?php

namespace App\Livewire\Admin;

use App\Models\Club;
use App\Models\Licence;
use App\Models\LicenceDeadline;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Управление дедлайнами лицензий')]
class LicenceDeadlineManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal States
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingDeadlineId = null;

    // Search & Filters
    public $search = '';
    public $filterLicence = '';
    public $filterClub = '';

    // Form Data
    #[Validate('required|integer|exists:licences,id')]
    public $licenceId = '';

    #[Validate('required|integer|exists:clubs,id')]
    public $clubId = '';

    #[Validate('required|date')]
    public $startAt = '';

    #[Validate('required|date|after:startAt')]
    public $endAt = '';

    // Relationships Data
    public $licences = [];
    public $clubs = [];

    // Permissions
    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        // Authorization
        // $this->authorize('view-licence-deadlines');

        // Set permissions
        $user = auth()->user();
        $this->canCreate = $user ? $user->can('create-licence-deadlines') : false;
        $this->canEdit = $user ? $user->can('manage-licence-deadlines') : false;
        $this->canDelete = $user ? $user->can('delete-licence-deadlines') : false;

        // Load relationship data
        $this->loadRelationships();
    }

    public function loadRelationships()
    {
        $this->licences = Licence::with(['season', 'league'])
            ->orderBy('created_at', 'desc')
            ->get();
        $this->clubs = Club::orderBy('short_name_ru')->get();
    }

    public function updatedSearch()
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

    public function getDeadlines()
    {
        $query = LicenceDeadline::with(['licence.season', 'licence.league', 'club']);

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('club', function ($cq) {
                    $cq->where('short_name_ru', 'like', '%' . $this->search . '%')
                        ->orWhere('short_name_kk', 'like', '%' . $this->search . '%')
                        ->orWhere('full_name_ru', 'like', '%' . $this->search . '%')
                        ->orWhere('full_name_kk', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('licence', function ($lq) {
                    $lq->where('title_ru', 'like', '%' . $this->search . '%')
                        ->orWhere('title_kk', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Filter by licence
        if (!empty($this->filterLicence)) {
            $query->where('licence_id', $this->filterLicence);
        }

        // Filter by club
        if (!empty($this->filterClub)) {
            $query->where('club_id', $this->filterClub);
        }

        return $query->orderBy('start_at', 'desc')->paginate(20);
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function createDeadline()
    {
        $this->authorize('create-licence-deadlines');

        $this->validate();

        LicenceDeadline::create([
            'licence_id' => $this->licenceId,
            'club_id' => $this->clubId,
            'start_at' => $this->startAt,
            'end_at' => $this->endAt,
        ]);

        $this->resetForm();
        session()->flash('message', 'Дедлайн успешно создан');
    }

    public function editDeadline($deadlineId)
    {
        $deadline = LicenceDeadline::findOrFail($deadlineId);
        $this->authorize('manage-licence-deadlines');

        $this->editingDeadlineId = $deadline->id;
        $this->licenceId = $deadline->licence_id;
        $this->clubId = $deadline->club_id;
        $this->startAt = $deadline->start_at->format('Y-m-d');
        $this->endAt = $deadline->end_at->format('Y-m-d');

        $this->showEditModal = true;
    }

    public function updateDeadline()
    {
        $this->authorize('manage-licence-deadlines');

        $deadline = LicenceDeadline::findOrFail($this->editingDeadlineId);

        $this->validate();

        $deadline->update([
            'licence_id' => $this->licenceId,
            'club_id' => $this->clubId,
            'start_at' => $this->startAt,
            'end_at' => $this->endAt,
        ]);

        $this->resetForm();
        session()->flash('message', 'Дедлайн успешно обновлен');
    }

    public function deleteDeadline($deadlineId)
    {
        $this->authorize('delete-licence-deadlines');

        $deadline = LicenceDeadline::findOrFail($deadlineId);
        $deadline->delete();

        session()->flash('message', 'Дедлайн успешно удален');
    }

    public function closeCreateModal()
    {
        $this->resetForm();
    }

    public function closeEditModal()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->reset([
            'editingDeadlineId',
            'licenceId',
            'clubId',
            'startAt',
            'endAt'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.licence-deadline-management', [
            'deadlines' => $this->getDeadlines(),
        ])->layout(get_user_layout());
    }
}
