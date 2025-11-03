<?php

namespace App\Livewire\Club;

use App\Models\Club;
use App\Models\ClubTeam;
use App\Models\ClubType;
use App\Models\League;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

#[Title('Мои клубы')]
class MyClubs extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'tailwind';

    // Modal States
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingClubId = null;

    // Search & Filters
    public $search = '';
    public $filterType = '';
    public $filterLeague = '';

    // Form Data - Required Fields
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

    #[Validate('nullable|string')]
    public $descriptionRu = '';

    #[Validate('nullable|string')]
    public $descriptionKk = '';

    #[Validate('nullable|string')]
    public $descriptionEn = '';

    #[Validate('required|string|size:12')]
    public $bin = '';

    #[Validate('required|date')]
    public $foundationDate = '';

    #[Validate('required|string|max:500')]
    public $legalAddress = '';

    #[Validate('required|string|max:500')]
    public $actualAddress = '';

    #[Validate('nullable|url|max:255')]
    public $website = '';

    #[Validate('nullable|email|max:255')]
    public $email = '';

    #[Validate('nullable|string|max:50')]
    public $phoneNumber = '';

    #[Validate('nullable|integer|exists:club_types,id')]
    public $typeId = '';

    #[Validate('nullable|integer|exists:clubs,id')]
    public $parentId = '';

    #[Validate('nullable|image|max:2048')]
    public $image = null;

    public $imageUrl = '';
    public $currentImage = '';

    public $verified = false;

    // Relationships Data
    public $clubTypes = [];
    public $leagues = [];
    public $parentClubs = [];

    // Permissions
    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        // Authorization (uncomment after setting up club-administrator user)
        // $this->authorize('view-clubs');

        $user = auth()->user();

        // Set permissions
        $this->canCreate = $user ? $user->can('create-clubs') : false;
        $this->canEdit = $user ? $user->can('manage-clubs') : false;
        $this->canDelete = $user ? $user->can('delete-clubs') : false;

        // Load relationship data
        $this->loadRelationships();
    }

    public function loadRelationships()
    {
        $this->clubTypes = ClubType::where('is_active', true)->orderBy('title_ru')->get();
        $this->leagues = League::where('is_active', true)->orderBy('level')->get();

        // Load only clubs that belong to this user
        $userId = auth()->id();
        $this->parentClubs = Club::whereHas('club_teams', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->orderBy('full_name_ru')->get();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterLeague()
    {
        $this->resetPage();
    }

    public function getClubs()
    {
        $userId = auth()->id();

        $query = Club::with(['club_type', 'parent', 'club_teams.user'])
            ->whereHas('club_teams', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });

        // Search across all language variants
        if ($this->search) {
            $query->where(function($q) {
                $q->where('full_name_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('full_name_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('full_name_en', 'like', '%' . $this->search . '%')
                  ->orWhere('short_name_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('short_name_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('short_name_en', 'like', '%' . $this->search . '%')
                  ->orWhere('bin', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Filters
        if (!empty($this->filterType)) {
            $query->where('type_id', $this->filterType);
        }

        return $query->orderBy('created_at', 'desc')->paginate(12);
    }

    public function createClub()
    {
        $this->authorize('create-clubs');

        $this->validate([
            'fullNameRu' => 'required|string|max:255',
            'fullNameKk' => 'required|string|max:255',
            'shortNameRu' => 'required|string|max:100',
            'shortNameKk' => 'required|string|max:100',
            'bin' => 'required|string|size:12|unique:clubs,bin',
            'foundationDate' => 'required|date',
            'legalAddress' => 'required|string|max:500',
            'actualAddress' => 'required|string|max:500',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        $imageUrl = null;
        if ($this->image) {
            $imageUrl = $this->image->store('clubs', 'public');
        }

        $club = Club::create([
            'full_name_ru' => $this->fullNameRu,
            'full_name_kk' => $this->fullNameKk,
            'full_name_en' => $this->fullNameEn,
            'short_name_ru' => $this->shortNameRu,
            'short_name_kk' => $this->shortNameKk,
            'short_name_en' => $this->shortNameEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'bin' => $this->bin,
            'foundation_date' => $this->foundationDate,
            'legal_address' => $this->legalAddress,
            'actual_address' => $this->actualAddress,
            'website' => $this->website,
            'email' => $this->email,
            'phone_number' => $this->phoneNumber,
            'type_id' => $this->typeId ?: null,
            'parent_id' => $this->parentId ?: null,
            'image_url' => $imageUrl,
            'verified' => false,
        ]);

        // Create club_team relationship
        ClubTeam::create([
            'club_id' => $club->id,
            'user_id' => auth()->id(),
            'role_id' => auth()->user()->role_id,
        ]);

        $this->resetForm();
        $this->loadRelationships();
        session()->flash('message', 'Клуб успешно создан');
    }

    public function editClub($clubId)
    {
        $club = Club::findOrFail($clubId);

        // Check if user has access to this club
        $this->authorize('manage-clubs');
        $this->checkClubAccess($club);

        $this->editingClubId = $club->id;
        $this->fullNameRu = $club->full_name_ru;
        $this->fullNameKk = $club->full_name_kk;
        $this->fullNameEn = $club->full_name_en ?? '';
        $this->shortNameRu = $club->short_name_ru;
        $this->shortNameKk = $club->short_name_kk;
        $this->shortNameEn = $club->short_name_en ?? '';
        $this->descriptionRu = $club->description_ru ?? '';
        $this->descriptionKk = $club->description_kk ?? '';
        $this->descriptionEn = $club->description_en ?? '';
        $this->bin = $club->bin;
        $this->foundationDate = $club->foundation_date->format('Y-m-d');
        $this->legalAddress = $club->legal_address;
        $this->actualAddress = $club->actual_address;
        $this->website = $club->website ?? '';
        $this->email = $club->email ?? '';
        $this->phoneNumber = $club->phone_number ?? '';
        $this->typeId = $club->type_id ?? '';
        $this->parentId = $club->parent_id ?? '';
        $this->currentImage = $club->image_url ?? '';
        $this->verified = $club->verified;

        $this->showEditModal = true;
    }

    public function updateClub()
    {
        $this->authorize('manage-clubs');

        $club = Club::findOrFail($this->editingClubId);
        $this->checkClubAccess($club);

        $this->validate([
            'fullNameRu' => 'required|string|max:255',
            'fullNameKk' => 'required|string|max:255',
            'shortNameRu' => 'required|string|max:100',
            'shortNameKk' => 'required|string|max:100',
            'bin' => 'required|string|size:12|unique:clubs,bin,' . $this->editingClubId,
            'foundationDate' => 'required|date',
            'legalAddress' => 'required|string|max:500',
            'actualAddress' => 'required|string|max:500',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        $imageUrl = $club->image_url;
        if ($this->image) {
            // Delete old image if exists
            if ($club->image_url) {
                \Storage::disk('public')->delete($club->image_url);
            }
            $imageUrl = $this->image->store('clubs', 'public');
        }

        $club->update([
            'full_name_ru' => $this->fullNameRu,
            'full_name_kk' => $this->fullNameKk,
            'full_name_en' => $this->fullNameEn,
            'short_name_ru' => $this->shortNameRu,
            'short_name_kk' => $this->shortNameKk,
            'short_name_en' => $this->shortNameEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'bin' => $this->bin,
            'foundation_date' => $this->foundationDate,
            'legal_address' => $this->legalAddress,
            'actual_address' => $this->actualAddress,
            'website' => $this->website,
            'email' => $this->email,
            'phone_number' => $this->phoneNumber,
            'type_id' => $this->typeId ?: null,
            'parent_id' => $this->parentId ?: null,
            'image_url' => $imageUrl,
        ]);

        $this->resetForm();
        $this->loadRelationships();
        session()->flash('message', 'Клуб успешно обновлен');
    }

    public function deleteClub($clubId)
    {
        $this->authorize('delete-clubs');

        $club = Club::findOrFail($clubId);
        $this->checkClubAccess($club);

        // Check if this is the only user in club_team for this club
        $teamMembersCount = ClubTeam::where('club_id', $club->id)->count();

        if ($teamMembersCount > 1) {
            session()->flash('error', 'Невозможно удалить клуб. В команде клуба есть другие участники.');
            return;
        }

        // Delete club_team relationships first
        ClubTeam::where('club_id', $club->id)->delete();

        // Delete the club
        $club->delete();

        $this->loadRelationships();
        session()->flash('message', 'Клуб успешно удален');
    }

    public function leaveClub($clubId)
    {
        $club = Club::findOrFail($clubId);
        $this->checkClubAccess($club);

        // Remove user from club_teams
        ClubTeam::where('club_id', $club->id)
            ->where('user_id', auth()->id())
            ->delete();

        $this->loadRelationships();
        session()->flash('message', 'Вы вышли из клуба "' . $club->short_name_ru . '"');
    }

    /**
     * Check if current user has access to the club
     */
    private function checkClubAccess(Club $club)
    {
        $userId = auth()->id();
        $hasAccess = ClubTeam::where('club_id', $club->id)
            ->where('user_id', $userId)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'У вас нет доступа к этому клубу');
        }
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
    }

    private function resetForm()
    {
        $this->reset([
            'fullNameRu', 'fullNameKk', 'fullNameEn',
            'shortNameRu', 'shortNameKk', 'shortNameEn',
            'descriptionRu', 'descriptionKk', 'descriptionEn',
            'bin', 'foundationDate', 'legalAddress', 'actualAddress',
            'website', 'email', 'phoneNumber', 'typeId', 'parentId',
            'image', 'imageUrl', 'currentImage', 'verified',
            'showCreateModal', 'showEditModal', 'editingClubId'
        ]);
    }

    public function render()
    {
        return view('livewire.club.my-clubs', [
            'clubs' => $this->getClubs(),
        ])->layout(get_user_layout());
    }
}
