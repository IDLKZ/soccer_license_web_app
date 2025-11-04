<?php

namespace App\Livewire\Club;

use App\Constants\ClubTypeConstants;
use App\Constants\LeagueConstants;
use App\Models\Club;
use App\Models\ClubTeam;
use App\Models\ClubType;
use App\Models\League;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('Управление клубами')]
class ClubManagement extends Component
{
    use WithFileUploads, WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal States
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingClubId = null;

    // Search & Filters
    public $search = '';
    public $filterType = '';

    // Form Data
    #[Validate('required|string|max:255')]
    public $fullNameRu = '';

    #[Validate('required|string|max:255')]
    public $fullNameKk = '';

    #[Validate('nullable|string|max:255')]
    public $fullNameEn = '';

    #[Validate('required|string|max:100')]
    public $shortNameRu = '';

    #[Validate('required|string|max:100')]
    public $shortNameKk = '';

    #[Validate('nullable|string|max:100')]
    public $shortNameEn = '';

    #[Validate('required|string|max:255')]
    public $bin = '';

    #[Validate('required|date')]
    public $foundationDate = '';

    #[Validate('required|string|max:500')]
    public $legalAddress = '';

    #[Validate('required|string|max:500')]
    public $actualAddress = '';

    #[Validate('nullable|email|max:255')]
    public $email = '';

    #[Validate('nullable|string|max:50')]
    public $phoneNumber = '';

    #[Validate('nullable|url|max:255')]
    public $website = '';

    #[Validate('nullable|string|max:1000')]
    public $descriptionRu = '';

    #[Validate('nullable|string|max:1000')]
    public $descriptionKk = '';

    #[Validate('nullable|string|max:1000')]
    public $descriptionEn = '';

    #[Validate('required|integer|exists:club_types,id')]
    public $typeId = '';

    #[Validate('required|integer|exists:users,id')]
    public $administratorId = '';

    #[Validate('nullable|image|max:2048')]
    public $clubLogo = null;

    public $existingLogoUrl = null;

    public $verified = false;

    // Relationships Data
    public $clubTypes = [];
    public $administrators = [];

    // Permissions
    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('view-clubs');

        // Set permissions
        $user = auth()->user();

        // Only administrative users can create clubs
        $this->canCreate = $user && $user->role && $user->role->is_administrative && $user->can('create-clubs');

        $this->canEdit = $user ? $user->can('manage-clubs') : false;
        $this->canDelete = $user ? $user->can('delete-clubs') : false;

        // Load relationship data
        $this->loadRelationshipData();
    }

    public function loadRelationshipData()
    {
        $this->clubTypes = ClubType::orderBy('title_ru')->get();

        // Get users who can be club administrators (non-administrative roles)
        $this->administrators = User::where('is_active', true)
            ->whereHas('role', function($query) {
                $query->where('is_administrative', false);
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    
    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function getClubs()
    {
        $query = Club::with(['club_type', 'club_teams.user']);

        // Get clubs managed by the current user (for non-administrative roles)
        $user = auth()->user();
        if ($user && $user->role && !$user->role->is_administrative) {
            // Get clubs where the user is a team member through club_teams
            $userClubIds = ClubTeam::where('user_id', $user->id)->pluck('club_id')->toArray();
            $query->whereIn('id', $userClubIds);
        }

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('full_name_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('full_name_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('full_name_en', 'like', '%' . $this->search . '%')
                  ->orWhere('short_name_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('short_name_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('short_name_en', 'like', '%' . $this->search . '%')
                  ->orWhere('bin', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone_number', 'like', '%' . $this->search . '%');
            });
        }

        // Filters
        if (!empty($this->filterType)) {
            $query->where('type_id', $this->filterType);
        }

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    // Method to handle pagination properly
    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function createClub()
    {
        $this->authorize('create-clubs');

        $this->validate();

        DB::beginTransaction();
        try {
            // Handle logo upload
            $logoPath = null;
            if ($this->clubLogo) {
                $logoPath = $this->clubLogo->store('club-logos', 'public');
            }

            $club = Club::create([
                'full_name_ru' => $this->fullNameRu,
                'full_name_kk' => $this->fullNameKk,
                'full_name_en' => $this->fullNameEn,
                'short_name_ru' => $this->shortNameRu,
                'short_name_kk' => $this->shortNameKk,
                'short_name_en' => $this->shortNameEn,
                'bin' => $this->bin,
                'foundation_date' => $this->foundationDate,
                'legal_address' => $this->legalAddress,
                'actual_address' => $this->actualAddress,
                'email' => $this->email,
                'phone_number' => $this->phoneNumber,
                'website' => $this->website,
                'description_ru' => $this->descriptionRu,
                'description_kk' => $this->descriptionKk,
                'description_en' => $this->descriptionEn,
                'type_id' => $this->typeId,
                'image_url' => $logoPath ? Storage::url($logoPath) : null,
                'verified' => (bool) $this->verified,
            ]);

            // Create club team relationship with administrator
            if ($this->administratorId) {
                ClubTeam::create([
                    'club_id' => $club->id,
                    'user_id' => $this->administratorId,
                    'role_id' => 1, // Club Administrator role - you might need to adjust this
                ]);
            }

            DB::commit();

            $this->resetForm();
            $this->showCreateModal = false;
            session()->flash('message', 'Клуб успешно создан');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ошибка при создании клуба: ' . $e->getMessage());
        }
    }

    public function editClub($clubId)
    {
        $club = Club::findOrFail($clubId);
        $this->authorize('manage-clubs');

        // Check if user can edit this club (for non-administrative roles)
        $user = auth()->user();
        if ($user && $user->role && !$user->role->is_administrative) {
            $userClubIds = ClubTeam::where('user_id', $user->id)->pluck('club_id')->toArray();
            if (!in_array($club->id, $userClubIds)) {
                session()->flash('error', 'У вас нет прав для редактирования этого клуба');
                return;
            }
        }

        $this->editingClubId = $club->id;
        $this->fullNameRu = $club->full_name_ru;
        $this->fullNameKk = $club->full_name_kk;
        $this->fullNameEn = $club->full_name_en ?? '';
        $this->shortNameRu = $club->short_name_ru;
        $this->shortNameKk = $club->short_name_kk;
        $this->shortNameEn = $club->short_name_en ?? '';
        $this->bin = $club->bin;
        $this->foundationDate = $club->foundation_date->format('Y-m-d');
        $this->legalAddress = $club->legal_address;
        $this->actualAddress = $club->actual_address;
        $this->email = $club->email ?? '';
        $this->phoneNumber = $club->phone_number ?? '';
        $this->website = $club->website ?? '';
        $this->descriptionRu = $club->description_ru ?? '';
        $this->descriptionKk = $club->description_kk ?? '';
        $this->descriptionEn = $club->description_en ?? '';
        $this->typeId = $club->type_id;
        $this->verified = $club->verified;
        $this->existingLogoUrl = $club->image_url;

        // Get current administrator
        $currentTeam = ClubTeam::where('club_id', $club->id)->first();
        $this->administratorId = $currentTeam ? $currentTeam->user_id : '';

        $this->showEditModal = true;
    }

    public function updateClub()
    {
        $this->authorize('manage-clubs');

        $club = Club::findOrFail($this->editingClubId);

        // Check if user can edit this club (for non-administrative roles)
        $user = auth()->user();
        if ($user && $user->role && !$user->role->is_administrative) {
            $userClubIds = ClubTeam::where('user_id', $user->id)->pluck('club_id')->toArray();
            if (!in_array($club->id, $userClubIds)) {
                session()->flash('error', 'У вас нет прав для редактирования этого клуба');
                return;
            }
        }

        $this->validate();

        DB::beginTransaction();
        try {
            // Handle logo upload
            $logoPath = null;
            if ($this->clubLogo) {
                // Delete old logo if exists
                if ($club->image_url) {
                    $oldPath = str_replace('/storage/', '', $club->image_url);
                    Storage::disk('public')->delete($oldPath);
                }
                $logoPath = $this->clubLogo->store('club-logos', 'public');
            }

            $clubData = [
                'full_name_ru' => $this->fullNameRu,
                'full_name_kk' => $this->fullNameKk,
                'full_name_en' => $this->fullNameEn,
                'short_name_ru' => $this->shortNameRu,
                'short_name_kk' => $this->shortNameKk,
                'short_name_en' => $this->shortNameEn,
                'bin' => $this->bin,
                'foundation_date' => $this->foundationDate,
                'legal_address' => $this->legalAddress,
                'actual_address' => $this->actualAddress,
                'email' => $this->email,
                'phone_number' => $this->phoneNumber,
                'website' => $this->website,
                'description_ru' => $this->descriptionRu,
                'description_kk' => $this->descriptionKk,
                'description_en' => $this->descriptionEn,
                'type_id' => $this->typeId,
                'verified' => (bool) $this->verified,
            ];

            // Only update image_url if new logo was uploaded
            if ($logoPath) {
                $clubData['image_url'] = Storage::url($logoPath);
            }

            $club->update($clubData);

            // Update club team relationship
            ClubTeam::where('club_id', $club->id)->delete();

            if ($this->administratorId) {
                ClubTeam::create([
                    'club_id' => $club->id,
                    'user_id' => $this->administratorId,
                    'role_id' => 1, // Club Administrator role
                ]);
            }

            DB::commit();

            $this->resetForm();
            $this->showEditModal = false;
            session()->flash('message', 'Клуб успешно обновлен');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ошибка при обновлении клуба: ' . $e->getMessage());
        }
    }

    public function leaveClub($clubId)
    {
        $user = auth()->user();
        $club = Club::findOrFail($clubId);

        // Only non-administrative users can leave clubs
        if (!$user || !$user->role || $user->role->is_administrative) {
            session()->flash('error', 'Административные пользователи не могут покинуть клуб');
            return;
        }

        try {
            // Remove user from club team
            $deleted = ClubTeam::where('club_id', $club->id)
                ->where('user_id', $user->id)
                ->delete();

            if ($deleted) {
                session()->flash('message', 'Вы успешно вышли из клуба');
            } else {
                session()->flash('error', 'Вы не состоите в этом клубе');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при выходе из клуба: ' . $e->getMessage());
        }
    }

    public function deleteClub($clubId)
    {
        $this->authorize('delete-clubs');

        $club = Club::findOrFail($clubId);
        $user = auth()->user();

        // Only administrative users can delete clubs
        if ($user && $user->role && !$user->role->is_administrative) {
            session()->flash('error', 'У вас нет прав для удаления клуба. Вы можете только выйти из клуба.');
            return;
        }

        try {
            // Check if club has applications or other important data
            $hasApplications = $club->applications()->count() > 0;

            if ($hasApplications) {
                session()->flash('error', 'Нельзя удалить клуб с существующими заявками');
                return;
            }

            // Delete club team relationships first
            ClubTeam::where('club_id', $club->id)->delete();

            // Delete club logo if exists
            if ($club->image_url) {
                $oldPath = str_replace('/storage/', '', $club->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            // Delete the club
            $club->delete();

            session()->flash('message', 'Клуб успешно удален');
        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при удалении клуба: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
            'fullNameRu', 'fullNameKk', 'fullNameEn',
            'shortNameRu', 'shortNameKk', 'shortNameEn',
            'bin', 'foundationDate', 'legalAddress', 'actualAddress',
            'email', 'phoneNumber', 'website',
            'descriptionRu', 'descriptionKk', 'descriptionEn',
            'typeId', 'administratorId', 'verified',
            'clubLogo', 'existingLogoUrl'
        ]);
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
        $this->editingClubId = null;
    }

    public function render()
    {
        return view('livewire.club.club-management', [
            'clubs' => $this->getClubs(),
        ])->layout(get_user_layout());
    }
}