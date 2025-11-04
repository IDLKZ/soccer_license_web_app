<?php

namespace App\Livewire\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('Управление пользователями')]
class UserManagement extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal States
    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingUserId = null;

    // Search & Filters
    public $search = '';
    public $filterRole = '';
    public $filterStatus = '';

    // Form Data
    #[Validate('required|string|max:255')]
    public $firstName = '';

    #[Validate('nullable|string|max:255')]
    public $lastName = '';

    #[Validate('nullable|string|max:255')]
    public $patronymic = '';

    #[Validate('required|email|max:255')]
    public $email = '';

    #[Validate('required|string|max:50')]
    public $phone = '';

    #[Validate('required|string|max:255')]
    public $username = '';

    #[Validate('nullable|string|size:12')]
    public $iin = '';

    #[Validate('nullable|string|max:255')]
    public $position = '';

    #[Validate('required|integer|exists:roles,id')]
    public $roleId = '';

    #[Validate('nullable|string|min:8')]
    public $password = '';

    #[Validate('nullable|image|max:2048')]
    public $photo = '';

    public $isActive = true;
    public $verified = false;

    // Relationships Data
    public $roles = [];

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
        // $this->authorize('view-users');

        // Set permissions
        $user = auth()->user();
        $this->canCreate = $user ? $user->can('create-users') : false;
        $this->canEdit = $user ? $user->can('manage-users') : false;
        $this->canDelete = $user ? $user->can('delete-users') : false;

        // Load relationship data
        $this->loadRoles();
    }

    public function loadRoles()
    {
        $this->roles = Role::orderBy('title_ru')->get();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterRole()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function getUsers()
    {
        $query = User::with('role');

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        // Filters
        if (!empty($this->filterRole)) {
            $query->where('role_id', $this->filterRole);
        }

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

    public function createUser()
    {
        $this->authorize('create-users');

        $this->validate([
            'firstName' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:50',
            'username' => 'required|string|max:255|unique:users,username',
            'roleId' => 'required|integer|exists:roles,id',
            'password' => 'required|string|min:8',
        ]);

        $userData = [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'patronymic' => $this->patronymic,
            'email' => $this->email,
            'phone' => $this->phone,
            'username' => $this->username,
            'iin' => $this->iin,
            'position' => $this->position,
            'role_id' => $this->roleId,
            'password' => Hash::make($this->password),
            'is_active' => (bool) $this->isActive,
            'verified' => (bool) $this->verified,
        ];

        // Handle photo upload
        if ($this->photo) {
            $photoPath = $this->photo->store('users/photos', 'public');
            $userData['image_url'] = $photoPath;
        }

        User::create($userData);

        $this->reset(['firstName', 'lastName', 'patronymic', 'email', 'phone', 'username', 'iin', 'position', 'roleId', 'password', 'photo', 'showCreateModal', 'isActive', 'verified']);
        session()->flash('message', 'Пользователь успешно создан');
    }

    public function editUser($userId)
    {
        $user = User::findOrFail($userId);
        $this->authorize('manage-users');

        $this->editingUserId = $user->id;
        $this->firstName = $user->first_name;
        $this->lastName = $user->last_name ?? '';
        $this->patronymic = $user->patronymic ?? '';
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->username = $user->username;
        $this->iin = $user->iin ?? '';
        $this->position = $user->position ?? '';
        $this->roleId = $user->role_id;
        $this->isActive = $user->is_active;
        $this->verified = $user->verified;
        $this->password = '';
        $this->photo = '';

        $this->showEditModal = true;
    }

    public function updateUser()
    {
        $this->authorize('manage-users');

        $user = User::findOrFail($this->editingUserId);

        $this->validate([
            'firstName' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->editingUserId,
            'phone' => 'required|string|max:50',
            'username' => 'required|string|max:255|unique:users,username,' . $this->editingUserId,
            'roleId' => 'required|integer|exists:roles,id',
            'password' => 'nullable|string|min:8',
        ]);

        $userData = [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'patronymic' => $this->patronymic,
            'email' => $this->email,
            'phone' => $this->phone,
            'username' => $this->username,
            'iin' => $this->iin,
            'position' => $this->position,
            'role_id' => $this->roleId,
            'is_active' => (bool) $this->isActive,
            'verified' => (bool) $this->verified,
        ];

        // Only update password if provided
        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }

        // Handle photo upload
        if ($this->photo) {
            // Delete old photo if exists
            if ($user->image_url) {
                Storage::disk('public')->delete($user->image_url);
            }
            $photoPath = $this->photo->store('users/photos', 'public');
            $userData['image_url'] = $photoPath;
        }

        $user->update($userData);

        $this->reset(['firstName', 'lastName', 'patronymic', 'email', 'phone', 'username', 'iin', 'position', 'roleId', 'password', 'photo', 'showEditModal', 'editingUserId', 'isActive', 'verified']);
        session()->flash('message', 'Пользователь успешно обновлен');
    }

    public function deleteUser($userId)
    {
        $this->authorize('delete-users');

        $user = User::findOrFail($userId);

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Нельзя удалить свою учетную запись');
            return;
        }

        // Delete user photo if exists
        if ($user->image_url) {
            Storage::disk('public')->delete($user->image_url);
        }

        $user->delete();

        session()->flash('message', 'Пользователь успешно удален');
    }

    public function toggleUserStatus($userId)
    {
        $this->authorize('manage-users');

        $user = User::findOrFail($userId);

        // Prevent deactivating yourself
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Нельзя деактивировать свою учетную запись');
            return;
        }

        $user->is_active = !$user->is_active;
        $user->save();

        session()->flash('message', 'Статус пользователя изменен');
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->reset(['firstName', 'lastName', 'patronymic', 'email', 'phone', 'username', 'iin', 'position', 'roleId', 'password', 'photo', 'isActive', 'verified']);
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->reset(['firstName', 'lastName', 'patronymic', 'email', 'phone', 'username', 'iin', 'position', 'roleId', 'password', 'photo', 'editingUserId', 'isActive', 'verified']);
    }

    public function getUserPhotoUrl($user)
    {
        if ($user->image_url) {
            return Storage::url($user->image_url);
        }
        return null;
    }

    public function render()
    {
        return view('livewire.admin.user-management', [
            'users' => $this->getUsers(),
        ])->layout(get_user_layout());
    }
}
