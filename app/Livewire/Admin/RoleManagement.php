<?php

namespace App\Livewire\Admin;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Управление ролями и правами')]
class RoleManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Modal States
    public $showCreateRoleModal = false;
    public $showEditRoleModal = false;
    public $showCreatePermissionModal = false;
    public $showEditPermissionModal = false;
    public $editingRoleId = null;
    public $editingPermissionId = null;

    // Active Tab
    public $activeTab = 'roles';

    // Search & Filters
    public $roleSearch = '';
    public $permissionSearch = '';
    public $filterRoleStatus = '';
    public $filterPermissionSystem = '';

    // Role Form Data
    #[Validate('required|string|max:255')]
    public $roleTitleRu = '';

    #[Validate('nullable|string|max:255')]
    public $roleTitleKk = '';

    #[Validate('nullable|string|max:255')]
    public $roleTitleEn = '';

    #[Validate('nullable|string|max:1000')]
    public $roleDescriptionRu = '';

    #[Validate('nullable|string|max:1000')]
    public $roleDescriptionKk = '';

    #[Validate('nullable|string|max:1000')]
    public $roleDescriptionEn = '';

    #[Validate('required|string|max:255')]
    public $roleValue = '';

    public $roleIsActive = true;
    public $roleCanRegister = false;
    public $roleIsSystem = false;
    public $roleIsAdministrative = false;

    // Permission Form Data
    #[Validate('required|string|max:255')]
    public $permissionTitleRu = '';

    #[Validate('nullable|string|max:255')]
    public $permissionTitleKk = '';

    #[Validate('nullable|string|max:255')]
    public $permissionTitleEn = '';

    #[Validate('nullable|string|max:1000')]
    public $permissionDescriptionRu = '';

    #[Validate('nullable|string|max:1000')]
    public $permissionDescriptionKk = '';

    #[Validate('nullable|string|max:1000')]
    public $permissionDescriptionEn = '';

    #[Validate('required|string|max:255')]
    public $permissionValue = '';

    public $permissionIsSystem = false;

    // Role Permissions Management
    public $rolePermissions = [];
    public $availablePermissions = [];

    // Permissions
    #[Locked]
    public $canCreateRoles = false;

    #[Locked]
    public $canEditRoles = false;

    #[Locked]
    public $canDeleteRoles = false;

    #[Locked]
    public $canCreatePermissions = false;

    #[Locked]
    public $canEditPermissions = false;

    #[Locked]
    public $canDeletePermissions = false;

    public function mount()
    {
        // Authorization
        $this->authorize('view-roles');

        // Set permissions based on user role
        $user = auth()->user();
        $this->canCreateRoles = $user ? $user->can('create-roles') : false;
        $this->canEditRoles = $user ? $user->can('manage-roles') : false;
        $this->canDeleteRoles = $user ? $user->can('delete-roles') : false;
        $this->canCreatePermissions = $user ? $user->can('create-permissions') : false;
        $this->canEditPermissions = $user ? $user->can('manage-permissions') : false;
        $this->canDeletePermissions = $user ? $user->can('delete-permissions') : false;

        // Load relationship data
        $this->loadAvailablePermissions();
    }

    public function loadAvailablePermissions()
    {
        $this->availablePermissions = Permission::orderBy('title_ru')->get();
    }

    public function updatedRoleSearch()
    {
        $this->resetPage();
    }

    public function updatedPermissionSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterRoleStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterPermissionSystem()
    {
        $this->resetPage();
    }

    public function getRoles()
    {
        $query = Role::with('permissions');

        // Search
        if ($this->roleSearch) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->roleSearch . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->roleSearch . '%')
                  ->orWhere('title_en', 'like', '%' . $this->roleSearch . '%')
                  ->orWhere('description_ru', 'like', '%' . $this->roleSearch . '%')
                  ->orWhere('description_kk', 'like', '%' . $this->roleSearch . '%')
                  ->orWhere('description_en', 'like', '%' . $this->roleSearch . '%')
                  ->orWhere('value', 'like', '%' . $this->roleSearch . '%');
            });
        }

        // Filters
        if ($this->filterRoleStatus !== '' && $this->filterRoleStatus !== null) {
            $query->where('is_active', $this->filterRoleStatus === '1');
        }

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    public function getPermissions()
    {
        $query = Permission::with('roles');

        // Search
        if ($this->permissionSearch) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->permissionSearch . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->permissionSearch . '%')
                  ->orWhere('title_en', 'like', '%' . $this->permissionSearch . '%')
                  ->orWhere('description_ru', 'like', '%' . $this->permissionSearch . '%')
                  ->orWhere('description_kk', 'like', '%' . $this->permissionSearch . '%')
                  ->orWhere('description_en', 'like', '%' . $this->permissionSearch . '%')
                  ->orWhere('value', 'like', '%' . $this->permissionSearch . '%');
            });
        }

        // Filters
        if ($this->filterPermissionSystem !== '' && $this->filterPermissionSystem !== null) {
            $query->where('is_system', $this->filterPermissionSystem === '1');
        }

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    // Method to handle pagination properly
    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    // Role CRUD Methods
    public function createRole()
    {
        $this->authorize('create-roles');

        $this->validate([
            'roleTitleRu' => 'required|string|max:255',
            'roleValue' => 'required|string|max:255|unique:roles,value',
        ]);

        $role = Role::create([
            'title_ru' => $this->roleTitleRu,
            'title_kk' => $this->roleTitleKk,
            'title_en' => $this->roleTitleEn,
            'description_ru' => $this->roleDescriptionRu,
            'description_kk' => $this->roleDescriptionKk,
            'description_en' => $this->roleDescriptionEn,
            'value' => $this->roleValue,
            'is_active' => (bool) $this->roleIsActive,
            'can_register' => (bool) $this->roleCanRegister,
            'is_system' => (bool) $this->roleIsSystem,
            'is_administrative' => (bool) $this->roleIsAdministrative,
        ]);

        // Attach permissions if selected
        if (!empty($this->rolePermissions)) {
            $role->permissions()->attach($this->rolePermissions);
        }

        $this->resetRoleForm();
        session()->flash('message', 'Роль успешно создана');
    }

    public function editRole($roleId)
    {
        $role = Role::findOrFail($roleId);
        $this->authorize('manage-roles');

        $this->editingRoleId = $role->id;
        $this->roleTitleRu = $role->title_ru;
        $this->roleTitleKk = $role->title_kk ?? '';
        $this->roleTitleEn = $role->title_en ?? '';
        $this->roleDescriptionRu = $role->description_ru ?? '';
        $this->roleDescriptionKk = $role->description_kk ?? '';
        $this->roleDescriptionEn = $role->description_en ?? '';
        $this->roleValue = $role->value;
        $this->roleIsActive = $role->is_active;
        $this->roleCanRegister = $role->can_register;
        $this->roleIsSystem = $role->is_system;
        $this->roleIsAdministrative = $role->is_administrative;
        $this->rolePermissions = $role->permissions->pluck('id')->toArray();

        $this->showEditRoleModal = true;
    }

    public function updateRole()
    {
        $this->authorize('manage-roles');

        $role = Role::findOrFail($this->editingRoleId);

        $this->validate([
            'roleTitleRu' => 'required|string|max:255',
            'roleValue' => 'required|string|max:255|unique:roles,value,' . $this->editingRoleId,
        ]);

        $roleData = [
            'title_ru' => $this->roleTitleRu,
            'title_kk' => $this->roleTitleKk,
            'title_en' => $this->roleTitleEn,
            'description_ru' => $this->roleDescriptionRu,
            'description_kk' => $this->roleDescriptionKk,
            'description_en' => $this->roleDescriptionEn,
            'value' => $this->roleValue,
            'is_active' => (bool) $this->roleIsActive,
            'can_register' => (bool) $this->roleCanRegister,
            'is_system' => (bool) $this->roleIsSystem,
            'is_administrative' => (bool) $this->roleIsAdministrative,
        ];

        $role->update($roleData);

        // Sync permissions
        $role->permissions()->sync($this->rolePermissions ?? []);

        $this->resetRoleForm();
        session()->flash('message', 'Роль успешно обновлена');
    }

    public function deleteRole($roleId)
    {
        $this->authorize('delete-roles');

        $role = Role::findOrFail($roleId);

        // Prevent deleting system roles
        if ($role->is_system) {
            session()->flash('error', 'Нельзя удалить системную роль');
            return;
        }

        // Prevent deleting roles with users
        if ($role->users()->count() > 0) {
            session()->flash('error', 'Нельзя удалить роль, привязанную к пользователям');
            return;
        }

        $role->delete();

        session()->flash('message', 'Роль успешно удалена');
    }

    public function toggleRoleStatus($roleId)
    {
        $this->authorize('manage-roles');

        $role = Role::findOrFail($roleId);

        // Prevent deactivating system roles
        if ($role->is_system) {
            session()->flash('error', 'Нельзя деактивировать системную роль');
            return;
        }

        $role->is_active = !$role->is_active;
        $role->save();

        session()->flash('message', 'Статус роли изменен');
    }

    // Permission CRUD Methods
    public function createPermission()
    {
        $this->authorize('create-permissions');

        $this->validate([
            'permissionTitleRu' => 'required|string|max:255',
            'permissionValue' => 'required|string|max:255|unique:permissions,value',
        ]);

        Permission::create([
            'title_ru' => $this->permissionTitleRu,
            'title_kk' => $this->permissionTitleKk,
            'title_en' => $this->permissionTitleEn,
            'description_ru' => $this->permissionDescriptionRu,
            'description_kk' => $this->permissionDescriptionKk,
            'description_en' => $this->permissionDescriptionEn,
            'value' => $this->permissionValue,
            'is_system' => (bool) $this->permissionIsSystem,
        ]);

        $this->resetPermissionForm();
        session()->flash('message', 'Право успешно создано');
    }

    public function editPermission($permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        $this->authorize('manage-permissions');

        $this->editingPermissionId = $permission->id;
        $this->permissionTitleRu = $permission->title_ru;
        $this->permissionTitleKk = $permission->title_kk ?? '';
        $this->permissionTitleEn = $permission->title_en ?? '';
        $this->permissionDescriptionRu = $permission->description_ru ?? '';
        $this->permissionDescriptionKk = $permission->description_kk ?? '';
        $this->permissionDescriptionEn = $permission->description_en ?? '';
        $this->permissionValue = $permission->value;
        $this->permissionIsSystem = $permission->is_system;

        $this->showEditPermissionModal = true;
    }

    public function updatePermission()
    {
        $this->authorize('manage-permissions');

        $permission = Permission::findOrFail($this->editingPermissionId);

        $this->validate([
            'permissionTitleRu' => 'required|string|max:255',
            'permissionValue' => 'required|string|max:255|unique:permissions,value,' . $this->editingPermissionId,
        ]);

        $permissionData = [
            'title_ru' => $this->permissionTitleRu,
            'title_kk' => $this->permissionTitleKk,
            'title_en' => $this->permissionTitleEn,
            'description_ru' => $this->permissionDescriptionRu,
            'description_kk' => $this->permissionDescriptionKk,
            'description_en' => $this->permissionDescriptionEn,
            'value' => $this->permissionValue,
            'is_system' => (bool) $this->permissionIsSystem,
        ];

        $permission->update($permissionData);

        $this->resetPermissionForm();
        session()->flash('message', 'Право успешно обновлено');
    }

    public function deletePermission($permissionId)
    {
        $this->authorize('delete-permissions');

        $permission = Permission::findOrFail($permissionId);

        // Prevent deleting system permissions
        if ($permission->is_system) {
            session()->flash('error', 'Нельзя удалить системное право');
            return;
        }

        // Prevent deleting permissions assigned to roles
        if ($permission->roles()->count() > 0) {
            session()->flash('error', 'Нельзя удалить право, привязанное к ролям');
            return;
        }

        $permission->delete();

        session()->flash('message', 'Право успешно удалено');
    }

    // Form Reset Methods
    public function resetRoleForm()
    {
        $this->reset([
            'roleTitleRu', 'roleTitleKk', 'roleTitleEn',
            'roleDescriptionRu', 'roleDescriptionKk', 'roleDescriptionEn',
            'roleValue', 'roleIsActive', 'roleCanRegister', 'roleIsSystem', 'roleIsAdministrative',
            'rolePermissions', 'showCreateRoleModal', 'showEditRoleModal', 'editingRoleId'
        ]);
        $this->loadAvailablePermissions();
    }

    public function resetPermissionForm()
    {
        $this->reset([
            'permissionTitleRu', 'permissionTitleKk', 'permissionTitleEn',
            'permissionDescriptionRu', 'permissionDescriptionKk', 'permissionDescriptionEn',
            'permissionValue', 'permissionIsSystem',
            'showCreatePermissionModal', 'showEditPermissionModal', 'editingPermissionId'
        ]);
    }

    // Modal Close Methods
    public function closeCreateRoleModal()
    {
        $this->showCreateRoleModal = false;
        $this->resetRoleForm();
    }

    public function closeEditRoleModal()
    {
        $this->showEditRoleModal = false;
        $this->resetRoleForm();
    }

    public function closeCreatePermissionModal()
    {
        $this->showCreatePermissionModal = false;
        $this->resetPermissionForm();
    }

    public function closeEditPermissionModal()
    {
        $this->showEditPermissionModal = false;
        $this->resetPermissionForm();
    }

    public function render()
    {
        return view('livewire.admin.role-management', [
            'roles' => $this->getRoles(),
            'permissions' => $this->getPermissions(),
        ])->layout(get_user_layout());
    }
}