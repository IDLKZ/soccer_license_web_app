<?php

namespace App\Livewire\Admin;

use App\Models\Season;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Управление сезонами')]
class SeasonManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal States
    public $showCreateSeasonModal = false;
    public $showEditSeasonModal = false;
    public $editingSeasonId = null;

    // Search & Filters
    public $search = '';
    public $filterStatus = '';

    // Season Form Data
    #[Validate('required|string|max:255')]
    public $titleRu = '';

    #[Validate('required|string|max:255')]
    public $titleKk = '';

    #[Validate('nullable|string|max:255')]
    public $titleEn = '';

    #[Validate('required|date')]
    public $startDate = '';

    #[Validate('required|date|after_or_equal:start_date')]
    public $endDate = '';

    public $isActive = true;

    // Permissions
    #[Locked]
    public $canCreateSeasons = false;

    #[Locked]
    public $canEditSeasons = false;

    #[Locked]
    public $canDeleteSeasons = false;

    public function mount()
    {
        // Authorization
        $this->authorize('view-seasons');

        // Set permissions
        $user = auth()->user();
        $this->canCreateSeasons = $user ? $user->can('create-seasons') : false;
        $this->canEditSeasons = $user ? $user->can('manage-seasons') : false;
        $this->canDeleteSeasons = $user ? $user->can('delete-seasons') : false;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function getSeasons()
    {
        $query = Season::query();

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%')
                  ->orWhere('value', 'like', '%' . $this->search . '%');
            });
        }

        // Filters
        if ($this->filterStatus !== '' && $this->filterStatus !== null) {
            $query->where('is_active', $this->filterStatus === '1');
        }

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    // Method to handle pagination properly
    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    // Method to open create modal
    public function openCreateSeasonModal()
    {
        $this->showCreateSeasonModal = true;
    }

    // Season CRUD Methods
    public function createSeason()
    {
        $this->authorize('create-seasons');

        $this->validate([
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:start_date',
        ]);

        Season::create([
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'start' => $this->startDate,
            'end' => $this->endDate,
            'is_active' => (bool) $this->isActive,
        ]);

        $this->resetSeasonForm();
        session()->flash('message', 'Сезон успешно создан');
    }

    public function editSeason($seasonId)
    {
        $season = Season::findOrFail($seasonId);
        $this->authorize('manage-seasons');

        $this->editingSeasonId = $season->id;
        $this->titleRu = $season->title_ru;
        $this->titleKk = $season->title_kk ?? '';
        $this->titleEn = $season->title_en ?? '';
        $this->startDate = $season->start ? $season->start->format('Y-m-d') : '';
        $this->endDate = $season->end ? $season->end->format('Y-m-d') : '';
        $this->isActive = $season->is_active;

        $this->showEditSeasonModal = true;
    }

    public function updateSeason()
    {
        $this->authorize('manage-seasons');

        $season = Season::findOrFail($this->editingSeasonId);

        $this->validate([
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:start_date',
        ]);

        $seasonData = [
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'start' => $this->startDate,
            'end' => $this->endDate,
            'is_active' => (bool) $this->isActive,
        ];

        $season->update($seasonData);

        $this->resetSeasonForm();
        session()->flash('message', 'Сезон успешно обновлен');
    }

    public function deleteSeason($seasonId)
    {
        $this->authorize('delete-seasons');

        $season = Season::findOrFail($seasonId);

        // Prevent deleting seasons with licences
        if ($season->licences()->count() > 0) {
            session()->flash('error', 'Нельзя удалить сезон, привязанный к лицензиям');
            return;
        }

        $season->delete();

        session()->flash('message', 'Сезон успешно удален');
    }

    public function toggleSeasonStatus($seasonId)
    {
        $this->authorize('manage-seasons');

        $season = Season::findOrFail($seasonId);
        $season->is_active = !$season->is_active;
        $season->save();

        session()->flash('message', 'Статус сезона изменен');
    }

    // Form Reset Methods
    public function resetSeasonForm()
    {
        $this->reset([
            'titleRu', 'titleKk', 'titleEn',
            'startDate', 'endDate', 'isActive',
            'showCreateSeasonModal', 'showEditSeasonModal', 'editingSeasonId'
        ]);
    }

    // Modal Close Methods
    public function closeCreateSeasonModal()
    {
        $this->showCreateSeasonModal = false;
        $this->resetSeasonForm();
    }

    public function closeEditSeasonModal()
    {
        $this->showEditSeasonModal = false;
        $this->resetSeasonForm();
    }

    public function render()
    {
        return view('livewire.admin.season-management', [
            'seasons' => $this->getSeasons(),
        ])->layout(get_user_layout());
    }
}