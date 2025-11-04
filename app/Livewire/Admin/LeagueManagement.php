<?php

namespace App\Livewire\Admin;

use App\Models\League;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Управление лигами')]
class LeagueManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal States
    public $showCreateLeagueModal = false;
    public $showEditLeagueModal = false;
    public $editingLeagueId = null;

    // Search & Filters
    public $search = '';
    public $filterStatus = '';

    // League Form Data
    #[Validate('required|string|max:255')]
    public $titleRu = '';

    #[Validate('required|string|max:255')]
    public $titleKk = '';

    #[Validate('nullable|string|max:255')]
    public $titleEn = '';

    #[Validate('required|string|max:255')]
    public $descriptionRu = '';

    #[Validate('required|string|max:255')]
    public $descriptionKk = '';

    #[Validate('nullable|string|max:255')]
    public $descriptionEn = '';

    #[Validate('required|integer|min:1|max:10')]
    public $level = 1;

    public $isActive = true;

    // Permissions
    #[Locked]
    public $canCreateLeagues = false;

    #[Locked]
    public $canEditLeagues = false;

    #[Locked]
    public $canDeleteLeagues = false;

    public function mount()
    {
        // Authorization
        $this->authorize('view-leagues');

        // Set permissions
        $user = auth()->user();
        $this->canCreateLeagues = $user ? $user->can('create-leagues') : false;
        $this->canEditLeagues = $user ? $user->can('manage-leagues') : false;
        $this->canDeleteLeagues = $user ? $user->can('delete-leagues') : false;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function getLeagues()
    {
        $query = League::query();

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%')
                  ->orWhere('description_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('description_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('description_en', 'like', '%' . $this->search . '%')
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
    public function openCreateLeagueModal()
    {
        $this->showCreateLeagueModal = true;
    }

    // League CRUD Methods
    public function createLeague()
    {
        $this->authorize('create-leagues');

        $this->validate([
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'descriptionRu' => 'required|string|max:255',
            'descriptionKk' => 'required|string|max:255',
            'level' => 'required|integer|min:1|max:10',
        ]);

        League::create([
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'level' => $this->level,
            'is_active' => (bool) $this->isActive,
        ]);

        $this->resetLeagueForm();
        session()->flash('message', 'Лига успешно создана');
    }

    public function editLeague($leagueId)
    {
        $league = League::findOrFail($leagueId);
        $this->authorize('manage-leagues');

        $this->editingLeagueId = $league->id;
        $this->titleRu = $league->title_ru;
        $this->titleKk = $league->title_kk ?? '';
        $this->titleEn = $league->title_en ?? '';
        $this->descriptionRu = $league->description_ru ?? '';
        $this->descriptionKk = $league->description_kk ?? '';
        $this->descriptionEn = $league->description_en ?? '';
        $this->level = $league->level ?? 1;
        $this->isActive = $league->is_active;

        $this->showEditLeagueModal = true;
    }

    public function updateLeague()
    {
        $this->authorize('manage-leagues');

        $league = League::findOrFail($this->editingLeagueId);

        $this->validate([
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'descriptionRu' => 'required|string|max:255',
            'descriptionKk' => 'required|string|max:255',
            'level' => 'required|integer|min:1|max:10',
        ]);

        $leagueData = [
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'level' => $this->level,
            'is_active' => (bool) $this->isActive,
        ];

        $league->update($leagueData);

        $this->resetLeagueForm();
        session()->flash('message', 'Лига успешно обновлена');
    }

    public function deleteLeague($leagueId)
    {
        $this->authorize('delete-leagues');

        $league = League::findOrFail($leagueId);

        // TODO: Add check for clubs when Club model is implemented
        // Prevent deleting leagues with clubs
        // if ($league->clubs()->count() > 0) {
        //     session()->flash('error', 'Нельзя удалить лигу, привязанную к клубам');
        //     return;
        // }

        $league->delete();

        session()->flash('message', 'Лига успешно удалена');
    }

    public function toggleLeagueStatus($leagueId)
    {
        $this->authorize('manage-leagues');

        $league = League::findOrFail($leagueId);
        $league->is_active = !$league->is_active;
        $league->save();

        session()->flash('message', 'Статус лиги изменен');
    }

    // Form Reset Methods
    public function resetLeagueForm()
    {
        $this->reset([
            'titleRu', 'titleKk', 'titleEn',
            'descriptionRu', 'descriptionKk', 'descriptionEn',
            'level', 'isActive',
            'showCreateLeagueModal', 'showEditLeagueModal', 'editingLeagueId'
        ]);
    }

    // Modal Close Methods
    public function closeCreateLeagueModal()
    {
        $this->showCreateLeagueModal = false;
        $this->resetLeagueForm();
    }

    public function closeEditLeagueModal()
    {
        $this->showEditLeagueModal = false;
        $this->resetLeagueForm();
    }

    public function render()
    {
        return view('livewire.admin.league-management', [
            'leagues' => $this->getLeagues(),
        ])->layout(get_user_layout());
    }
}