<?php

namespace App\Livewire\Admin;

use App\Models\Club;
use App\Models\ClubType;
use App\Constants\ClubTypeConstants;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('Управление клубами')]
class ClubManagement extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal States
    public $showCreateClubModal = false;
    public $showEditClubModal = false;
    public $editingClubId = null;

    // Search & Filters
    public $search = '';
    public $filterStatus = '';
    public $filterType = '';

    // Club Form Data
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

    #[Validate('required|string|size:12|unique:clubs,bin')]
    public $bin = '';

    #[Validate('nullable|date')]
    public $foundationDate = '';

    #[Validate('required|string|max:500')]
    public $legalAddress = '';

    #[Validate('required|string|max:500')]
    public $actualAddress = '';

    #[Validate('nullable|string|max:255')]
    public $website = '';

    #[Validate('nullable|email|max:255')]
    public $email = '';

    #[Validate('nullable|string|max:20')]
    public $phoneNumber = '';

    #[Validate('nullable|string|max:1000')]
    public $descriptionRu = '';

    #[Validate('nullable|string|max:1000')]
    public $descriptionKk = '';

    #[Validate('nullable|string|max:1000')]
    public $descriptionEn = '';

    #[Validate('nullable|integer|exists:club_types,id')]
    public $typeId = null;

    #[Validate('nullable|integer|exists:clubs,id')]
    public $parentId = null;

    public $verified = false;

    #[Validate('nullable|image|max:2048')]
    public $photo = '';

    // Permissions
    #[Locked]
    public $canCreateClubs = false;

    #[Locked]
    public $canEditClubs = false;

    #[Locked]
    public $canDeleteClubs = false;

    public function mount()
    {
        // Authorization
        $this->authorize('view-clubs');

        // Set permissions
        $user = auth()->user();
        $this->canCreateClubs = $user ? $user->can('create-clubs') : false;
        $this->canEditClubs = $user ? $user->can('manage-clubs') : false;
        $this->canDeleteClubs = $user ? $user->can('delete-clubs') : false;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function getClubs()
    {
        $query = Club::query();

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
                  ->orWhere('description_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('description_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('description_en', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('website', 'like', '%' . $this->search . '%');
            });
        }

        // Status Filter
        if ($this->filterStatus !== '' && $this->filterStatus !== null) {
            $query->where('verified', $this->filterStatus === '1');
        }

        // Type Filter
        if ($this->filterType !== '' && $this->filterType !== null) {
            $query->where('type_id', $this->filterType);
        }

        return $query->with(['club_type', 'parent'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
    }

    // Method to handle pagination properly
    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    // Method to open create modal
    public function openCreateClubModal()
    {
        $this->showCreateClubModal = true;
    }

    // Club CRUD Methods
    public function createClub()
    {
        $this->authorize('create-clubs');

        $this->validate([
            'fullNameRu' => 'required|string|max:255',
            'fullNameKk' => 'required|string|max:255',
            'shortNameRu' => 'required|string|max:100',
            'shortNameKk' => 'required|string|max:100',
            'bin' => 'required|string|size:12|unique:clubs,bin',
            'foundationDate' => 'nullable|date',
            'legalAddress' => 'required|string|max:500',
            'actualAddress' => 'required|string|max:500',
        ]);

        $clubData = [
            'full_name_ru' => $this->fullNameRu,
            'full_name_kk' => $this->fullNameKk,
            'full_name_en' => $this->fullNameEn,
            'short_name_ru' => $this->shortNameRu,
            'short_name_kk' => $this->shortNameKk,
            'short_name_en' => $this->shortNameEn,
            'bin' => $this->bin,
            'foundation_date' => $this->foundationDate ?: null,
            'legal_address' => $this->legalAddress,
            'actual_address' => $this->actualAddress,
            'website' => $this->website,
            'email' => $this->email,
            'phone_number' => $this->phoneNumber,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'type_id' => $this->typeId,
            'parent_id' => $this->parentId,
            'verified' => (bool) $this->verified,
        ];

        // Handle photo upload
        if ($this->photo) {
            $photoPath = $this->photo->store('clubs/photos', 'public');
            $clubData['image_url'] = $photoPath;
        }

        Club::create($clubData);

        $this->resetClubForm();
        session()->flash('message', 'Клуб успешно создан');
    }

    public function editClub($clubId)
    {
        $club = Club::findOrFail($clubId);
        $this->authorize('manage-clubs');

        $this->editingClubId = $club->id;
        $this->fullNameRu = $club->full_name_ru;
        $this->fullNameKk = $club->full_name_kk ?? '';
        $this->fullNameEn = $club->full_name_en ?? '';
        $this->shortNameRu = $club->short_name_ru;
        $this->shortNameKk = $club->short_name_kk ?? '';
        $this->shortNameEn = $club->short_name_en ?? '';
        $this->bin = $club->bin;
        $this->foundationDate = $club->foundation_date ? $club->foundation_date->format('Y-m-d') : '';
        $this->legalAddress = $club->legal_address ?? '';
        $this->actualAddress = $club->actual_address ?? '';
        $this->website = $club->website ?? '';
        $this->email = $club->email ?? '';
        $this->phoneNumber = $club->phone_number ?? '';
        $this->descriptionRu = $club->description_ru ?? '';
        $this->descriptionKk = $club->description_kk ?? '';
        $this->descriptionEn = $club->description_en ?? '';
        $this->typeId = $club->type_id;
        $this->parentId = $club->parent_id;
        $this->verified = $club->verified;
        $this->photo = '';

        $this->showEditClubModal = true;
    }

    public function updateClub()
    {
        $this->authorize('manage-clubs');

        $club = Club::findOrFail($this->editingClubId);

        $this->validate([
            'fullNameRu' => 'required|string|max:255',
            'fullNameKk' => 'required|string|max:255',
            'shortNameRu' => 'required|string|max:100',
            'shortNameKk' => 'required|string|max:100',
            'bin' => 'required|string|size:12|unique:clubs,bin,' . $this->editingClubId,
            'foundationDate' => 'nullable|date',
            'legalAddress' => 'required|string|max:500',
            'actualAddress' => 'required|string|max:500',
        ]);

        $clubData = [
            'full_name_ru' => $this->fullNameRu,
            'full_name_kk' => $this->fullNameKk,
            'full_name_en' => $this->fullNameEn,
            'short_name_ru' => $this->shortNameRu,
            'short_name_kk' => $this->shortNameKk,
            'short_name_en' => $this->shortNameEn,
            'bin' => $this->bin,
            'foundation_date' => $this->foundationDate ?: null,
            'legal_address' => $this->legalAddress,
            'actual_address' => $this->actualAddress,
            'website' => $this->website,
            'email' => $this->email,
            'phone_number' => $this->phoneNumber,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'type_id' => $this->typeId,
            'parent_id' => $this->parentId,
            'verified' => (bool) $this->verified,
        ];

        // Handle photo upload
        if ($this->photo) {
            // Delete old photo if exists
            if ($club->image_url) {
                Storage::disk('public')->delete($club->image_url);
            }
            $photoPath = $this->photo->store('clubs/photos', 'public');
            $clubData['image_url'] = $photoPath;
        }

        $club->update($clubData);

        $this->resetClubForm();
        session()->flash('message', 'Клуб успешно обновлен');
    }

    public function deleteClub($clubId)
    {
        $this->authorize('delete-clubs');

        $club = Club::findOrFail($clubId);

        // Prevent deleting clubs with teams
        if ($club->club_teams()->count() > 0) {
            session()->flash('error', 'Нельзя удалить клуб, привязанный к командам');
            return;
        }

        // Prevent deleting clubs with child clubs
        if ($club->clubs()->count() > 0) {
            session()->flash('error', 'Нельзя удалить клуб, имеющий дочерние клубы');
            return;
        }

        // Delete club photo if exists
        if ($club->image_url) {
            Storage::disk('public')->delete($club->image_url);
        }

        $club->delete();

        session()->flash('message', 'Клуб успешно удален');
    }

    public function toggleClubVerification($clubId)
    {
        $this->authorize('manage-clubs');

        $club = Club::findOrFail($clubId);
        $club->verified = !$club->verified;
        $club->save();

        session()->flash('message', 'Статус верификации клуба изменен');
    }

    // Form Reset Methods
    public function resetClubForm()
    {
        $this->reset([
            'fullNameRu', 'fullNameKk', 'fullNameEn',
            'shortNameRu', 'shortNameKk', 'shortNameEn',
            'bin', 'foundationDate', 'legalAddress', 'actualAddress',
            'website', 'email', 'phoneNumber',
            'descriptionRu', 'descriptionKk', 'descriptionEn',
            'typeId', 'parentId', 'verified', 'photo',
            'showCreateClubModal', 'showEditClubModal', 'editingClubId'
        ]);
    }

    // Modal Close Methods
    public function closeCreateClubModal()
    {
        $this->showCreateClubModal = false;
        $this->resetClubForm();
    }

    public function closeEditClubModal()
    {
        $this->showEditClubModal = false;
        $this->resetClubForm();
    }

    public function getClubPhotoUrl($club)
    {
        if ($club->image_url) {
            return Storage::url($club->image_url);
        }
        return null;
    }

    public function render()
    {
        return view('livewire.admin.club-management', [
            'clubs' => $this->getClubs(),
            'clubTypes' => ClubType::where('is_active', true)->get(),
            'allClubs' => Club::where('id', '!=', $this->editingClubId ?? 0)->get(),
        ])->layout(get_user_layout());
    }
}