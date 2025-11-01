<?php

namespace App\Livewire\Admin;

use App\Models\Club;
use App\Models\ClubTeam;
use App\Models\User;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Управление составом команд')]
class ClubTeamManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal States
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingTeamId = null;

    // Search & Filters
    public $search = '';
    public $filterClub = '';

    // Form Data
    #[Validate('required|integer|exists:clubs,id')]
    public $clubId = null;

    #[Validate('required|integer|exists:users,id')]
    public $userId = null;

    // Relationships Data
    public $clubs = [];
    public $users = [];

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
        $this->authorize('view-club-teams');

        // Set permissions
        $user = auth()->user();
        $this->canCreate = $user ? $user->can('create-club-teams') : false;
        $this->canEdit = $user ? $user->can('manage-club-teams') : false;
        $this->canDelete = $user ? $user->can('delete-club-teams') : false;

        // Load relationship data
        $this->loadClubs();
        $this->loadUsers();
    }

    public function loadClubs()
    {
        $this->clubs = Club::orderBy('short_name_ru')->get();
    }

    public function loadUsers()
    {
        $this->users = User::with('role')
            ->whereHas('role', function($query) {
                $query->where('is_administrative', false);
            })
            ->orderBy('first_name')
            ->get();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterClub()
    {
        $this->resetPage();
    }

    public function getTeams()
    {
        $query = ClubTeam::with(['club', 'user.role', 'role']);

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('user', function($userQuery) {
                    $userQuery->where('first_name', 'like', '%' . $this->search . '%')
                              ->orWhere('last_name', 'like', '%' . $this->search . '%')
                              ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('club', function($clubQuery) {
                    $clubQuery->where('short_name_ru', 'like', '%' . $this->search . '%')
                              ->orWhere('short_name_kk', 'like', '%' . $this->search . '%')
                              ->orWhere('full_name_ru', 'like', '%' . $this->search . '%')
                              ->orWhere('full_name_kk', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Filter by club
        if ($this->filterClub !== '' && $this->filterClub !== null) {
            $query->where('club_id', $this->filterClub);
        }

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    // Method to handle pagination properly
    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function createTeam()
    {
        $this->authorize('create-club-teams');

        $this->validate();

        // Get user's role_id
        $user = User::find($this->userId);

        if (!$user) {
            session()->flash('error', 'Пользователь не найден');
            return;
        }

        // Check if this user is already in this club's team
        $exists = ClubTeam::where('club_id', $this->clubId)
                          ->where('user_id', $this->userId)
                          ->exists();

        if ($exists) {
            session()->flash('error', 'Этот пользователь уже состоит в команде этого клуба');
            return;
        }

        ClubTeam::create([
            'club_id' => $this->clubId,
            'user_id' => $this->userId,
            'role_id' => $user->role_id,
        ]);

        $this->reset(['clubId', 'userId', 'showCreateModal']);
        session()->flash('message', 'Участник успешно добавлен в команду');
    }

    public function editTeam($teamId)
    {
        $team = ClubTeam::findOrFail($teamId);
        $this->authorize('manage-club-teams');

        $this->editingTeamId = $team->id;
        $this->clubId = $team->club_id;
        $this->userId = $team->user_id;

        $this->showEditModal = true;
    }

    public function updateTeam()
    {
        $this->authorize('manage-club-teams');

        $team = ClubTeam::findOrFail($this->editingTeamId);

        $this->validate();

        // Get user's role_id
        $user = User::find($this->userId);

        if (!$user) {
            session()->flash('error', 'Пользователь не найден');
            return;
        }

        // Check if this user is already in this club's team (excluding current record)
        $exists = ClubTeam::where('club_id', $this->clubId)
                          ->where('user_id', $this->userId)
                          ->where('id', '!=', $this->editingTeamId)
                          ->exists();

        if ($exists) {
            session()->flash('error', 'Этот пользователь уже состоит в команде этого клуба');
            return;
        }

        $team->update([
            'club_id' => $this->clubId,
            'user_id' => $this->userId,
            'role_id' => $user->role_id,
        ]);

        $this->reset(['clubId', 'userId', 'showEditModal', 'editingTeamId']);
        session()->flash('message', 'Участник команды успешно обновлен');
    }

    public function deleteTeam($teamId)
    {
        $this->authorize('delete-club-teams');

        $team = ClubTeam::findOrFail($teamId);
        $team->delete();

        session()->flash('message', 'Участник удален из команды');
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->reset(['clubId', 'userId']);
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->reset(['clubId', 'userId', 'editingTeamId']);
    }

    public function render()
    {
        return view('livewire.admin.club-team-management', [
            'teams' => $this->getTeams(),
        ])->layout(get_user_layout());
    }
}
