<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Управление ролями и правами</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Создание, редактирование и управление ролями и правами доступа</p>
        </div>
    </div>

    <!-- Success Messages -->
    @if(session()->has('message'))
    <div class="mb-4 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 dark:border-green-400 p-4 rounded">
        <div class="flex">
            <i class="fas fa-check-circle text-green-500 dark:text-green-400 mt-0.5"></i>
            <p class="ml-3 text-green-700 dark:text-green-300">{{ session('message') }}</p>
        </div>
    </div>
    @endif

    <!-- Error Messages -->
    @if(session()->has('error'))
    <div class="mb-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-400 p-4 rounded">
        <div class="flex">
            <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 mt-0.5"></i>
            <p class="ml-3 text-red-700 dark:text-red-300">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg border border-gray-200 dark:border-gray-700 mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex -mb-px">
                <button wire:click="$set('activeTab', 'roles')"
                        class="{{ $activeTab === 'roles' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }} py-4 px-6 text-sm font-medium border-b-2 focus:outline-none transition-colors">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Роли
                </button>
                <button wire:click="$set('activeTab', 'permissions')"
                        class="{{ $activeTab === 'permissions' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }} py-4 px-6 text-sm font-medium border-b-2 focus:outline-none transition-colors">
                    <i class="fas fa-key mr-2"></i>
                    Права доступа
                </button>
            </nav>
        </div>

        <!-- Roles Tab -->
        @if($activeTab === 'roles')
        <div class="p-6">
            <!-- Actions and Filters -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex gap-4 flex-1">
                    <div class="flex-1 max-w-md">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-search mr-1 text-gray-400 dark:text-gray-500"></i>
                            Поиск ролей
                        </label>
                        <input type="text"
                               wire:model.live.debounce.500ms="roleSearch"
                               placeholder="Название, описание, значение..."
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-toggle-on mr-1 text-gray-400 dark:text-gray-500"></i>
                            Статус
                        </label>
                        <select wire:model.live="filterRoleStatus"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                            <option value="">Все статусы</option>
                            <option value="1">Активные</option>
                            <option value="0">Неактивные</option>
                        </select>
                    </div>
                </div>
                @if($canCreateRoles)
                <button wire:click="$set('showCreateRoleModal', true)"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors duration-150">
                    <i class="fas fa-plus mr-2"></i>
                    Создать роль
                </button>
                @endif
            </div>

            @if($roles->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg dark:shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-shield-alt mr-1 text-gray-400 dark:text-gray-500"></i>
                                        Роль
                                    </div>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-info-circle mr-1 text-gray-400 dark:text-gray-500"></i>
                                        Описание
                                    </div>
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-key mr-1 text-gray-400 dark:text-gray-500"></i>
                                        Права
                                    </div>
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-toggle-on mr-1 text-gray-400 dark:text-gray-500"></i>
                                        Статус
                                    </div>
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-cogs mr-1 text-gray-400 dark:text-gray-500"></i>
                                        Действия
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($roles as $role)
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/30 transition-all duration-150 border-l-4 border-transparent hover:border-blue-400 dark:hover:border-blue-500">
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                            <i class="fas fa-shield-alt text-white"></i>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $role->title_ru }}
                                                @if($role->title_kk) <span class="text-xs text-gray-500 dark:text-gray-400">({{ $role->title_kk }})</span> @endif
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $role->value }}
                                                @if($role->is_system) <span class="ml-1 text-orange-500 dark:text-orange-400"><i class="fas fa-lock"></i></span> @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate">
                                        {{ $role->description_ru ?: 'Нет описания' }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-700">
                                        {{ $role->permissions->count() }} прав
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    @if($role->is_active)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-700">
                                        <i class="fas fa-check-circle mr-1 text-green-600 dark:text-green-400"></i>
                                        Активна
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-pink-100 dark:from-red-900/30 dark:to-pink-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-700">
                                        <i class="fas fa-times-circle mr-1 text-red-600 dark:text-red-400"></i>
                                        Неактивна
                                    </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($canEditRoles)
                                        <button wire:click="editRole({{ $role->id }})"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/30 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-150"
                                                title="Редактировать">
                                            <i class="fas fa-edit text-sm"></i>
                                        </button>
                                        @if(!$role->is_system)
                                        <button wire:click="toggleRoleStatus({{ $role->id }})"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-50 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300 transition-colors duration-150"
                                                title="Изменить статус">
                                            <i class="fas fa-toggle-on text-sm"></i>
                                        </button>
                                        @endif
                                        @endif
                                        @if($canDeleteRoles && !$role->is_system)
                                        <button wire:click="deleteRole({{ $role->id }})"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150"
                                                title="Удалить"
                                                onclick="return confirm('Вы уверены, что хотите удалить эту роль?')">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Пагинация -->
            @if($roles->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $roles->links('pagination::livewire-tailwind') }}
            </div>
            @endif
            @else
            <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col items-center">
                    <i class="fas fa-shield-alt text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Роли не найдены</p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Попробуйте изменить параметры фильтрации</p>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Permissions Tab -->
        @if($activeTab === 'permissions')
        <div class="p-6">
            <!-- Actions and Filters -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex gap-4 flex-1">
                    <div class="flex-1 max-w-md">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-search mr-1 text-gray-400 dark:text-gray-500"></i>
                            Поиск прав
                        </label>
                        <input type="text"
                               wire:model.live.debounce.500ms="permissionSearch"
                               placeholder="Название, описание, значение..."
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-lock mr-1 text-gray-400 dark:text-gray-500"></i>
                            Тип
                        </label>
                        <select wire:model.live="filterPermissionSystem"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                            <option value="">Все права</option>
                            <option value="1">Системные</option>
                            <option value="0">Пользовательские</option>
                        </select>
                    </div>
                </div>
                @if($canCreatePermissions)
                <button wire:click="$set('showCreatePermissionModal', true)"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors duration-150">
                    <i class="fas fa-plus mr-2"></i>
                    Создать право
                </button>
                @endif
            </div>

            @if($permissions->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg dark:shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-key mr-1 text-gray-400 dark:text-gray-500"></i>
                                        Право доступа
                                    </div>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-info-circle mr-1 text-gray-400 dark:text-gray-500"></i>
                                        Описание
                                    </div>
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-shield-alt mr-1 text-gray-400 dark:text-gray-500"></i>
                                        Роли
                                    </div>
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-cogs mr-1 text-gray-400 dark:text-gray-500"></i>
                                        Действия
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($permissions as $permission)
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/30 transition-all duration-150 border-l-4 border-transparent hover:border-blue-400 dark:hover:border-blue-500">
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center">
                                            <i class="fas fa-key text-white"></i>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $permission->title_ru }}
                                                @if($permission->title_kk) <span class="text-xs text-gray-500 dark:text-gray-400">({{ $permission->title_kk }})</span> @endif
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $permission->value }}
                                                @if($permission->is_system) <span class="ml-1 text-orange-500 dark:text-orange-400"><i class="fas fa-lock"></i></span> @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate">
                                        {{ $permission->description_ru ?: 'Нет описания' }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-700">
                                        {{ $permission->roles->count() }} ролей
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($canEditPermissions)
                                        <button wire:click="editPermission({{ $permission->id }})"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/30 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-150"
                                                title="Редактировать">
                                            <i class="fas fa-edit text-sm"></i>
                                        </button>
                                        @endif
                                        @if($canDeletePermissions && !$permission->is_system)
                                        <button wire:click="deletePermission({{ $permission->id }})"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150"
                                                title="Удалить"
                                                onclick="return confirm('Вы уверены, что хотите удалить это право?')">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Пагинация -->
            @if($permissions->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $permissions->links('pagination::livewire-tailwind') }}
            </div>
            @endif
            @else
            <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col items-center">
                    <i class="fas fa-key text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Права доступа не найдены</p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Попробуйте изменить параметры фильтрации</p>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Create Role Modal -->
    @if($showCreateRoleModal)
    <div wire:ignore.self class="fixed inset-0 overflow-y-auto" style="z-index: 9999;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="closeCreateRoleModal" style="z-index: 9998;">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-200 dark:border-gray-700 relative" style="z-index: 9999;">
                <form wire:submit.prevent="createRole">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Создание роли</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Titles -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Название (RU)*
                                    </label>
                                    <input type="text"
                                           wire:model="roleTitleRu"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('roleTitleRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Название (KK)
                                    </label>
                                    <input type="text"
                                           wire:model="roleTitleKk"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('roleTitleKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Название (EN)
                                    </label>
                                    <input type="text"
                                           wire:model="roleTitleEn"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('roleTitleEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Descriptions -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (RU)
                                    </label>
                                    <textarea wire:model="roleDescriptionRu"
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                    @error('roleDescriptionRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (KK)
                                    </label>
                                    <textarea wire:model="roleDescriptionKk"
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                    @error('roleDescriptionKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (EN)
                                    </label>
                                    <textarea wire:model="roleDescriptionEn"
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                    @error('roleDescriptionEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Value -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Значение (уникальное)*
                                </label>
                                <input type="text"
                                       wire:model="roleValue"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                @error('roleValue') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Checkboxes -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="roleIsActive" class="mr-2 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-400">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Активна</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="roleCanRegister" class="mr-2 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-400">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Может регистрироваться</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="roleIsSystem" class="mr-2 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-400">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Системная</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="roleIsAdministrative" class="mr-2 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-400">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Административная</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Permissions -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    <i class="fas fa-key mr-2 text-blue-500 dark:text-blue-400"></i>
                                    Права доступа
                                </label>
                                <div class="max-h-64 overflow-y-auto border-2 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg p-2">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                        @foreach($availablePermissions as $permission)
                                        <label class="flex items-start p-3 hover:bg-white dark:hover:bg-gray-600 rounded-lg cursor-pointer transition-colors border border-transparent hover:border-blue-300 dark:hover:border-blue-600">
                                            <input type="checkbox"
                                                   wire:model="rolePermissions"
                                                   value="{{ $permission->id }}"
                                                   class="mt-1 mr-3 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-400 rounded">
                                            <div class="flex-1">
                                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $permission->title_ru }}</span>
                                                @if($permission->description_ru)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($permission->description_ru, 50) }}</p>
                                                @endif
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Выбрано: <span class="font-semibold" x-text="$wire.rolePermissions.length">0</span> прав
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 dark:bg-blue-500 text-base font-medium text-white hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Создать
                        </button>
                        <button type="button"
                                wire:click="closeCreateRoleModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Role Modal -->
    @if($showEditRoleModal)
    <div wire:ignore.self class="fixed inset-0 overflow-y-auto" style="z-index: 9999;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="closeEditRoleModal" style="z-index: 9998;">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-200 dark:border-gray-700 relative" style="z-index: 9999;">
                <form wire:submit.prevent="updateRole">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Редактирование роли</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Titles -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Название (RU)*
                                    </label>
                                    <input type="text"
                                           wire:model="roleTitleRu"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('roleTitleRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Название (KK)
                                    </label>
                                    <input type="text"
                                           wire:model="roleTitleKk"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('roleTitleKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Название (EN)
                                    </label>
                                    <input type="text"
                                           wire:model="roleTitleEn"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('roleTitleEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Descriptions -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (RU)
                                    </label>
                                    <textarea wire:model="roleDescriptionRu"
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                    @error('roleDescriptionRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (KK)
                                    </label>
                                    <textarea wire:model="roleDescriptionKk"
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                    @error('roleDescriptionKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (EN)
                                    </label>
                                    <textarea wire:model="roleDescriptionEn"
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                    @error('roleDescriptionEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Value -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Значение (уникальное)*
                                </label>
                                <input type="text"
                                       wire:model="roleValue"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                @error('roleValue') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Checkboxes -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="roleIsActive" class="mr-2 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-400">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Активна</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="roleCanRegister" class="mr-2 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-400">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Может регистрироваться</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="roleIsSystem" class="mr-2 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-400">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Системная</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="roleIsAdministrative" class="mr-2 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-400">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Административная</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Permissions -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    <i class="fas fa-key mr-2 text-blue-500 dark:text-blue-400"></i>
                                    Права доступа
                                </label>
                                <div class="max-h-64 overflow-y-auto border-2 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg p-2">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                        @foreach($availablePermissions as $permission)
                                        <label class="flex items-start p-3 hover:bg-white dark:hover:bg-gray-600 rounded-lg cursor-pointer transition-colors border border-transparent hover:border-blue-300 dark:hover:border-blue-600">
                                            <input type="checkbox"
                                                   wire:model="rolePermissions"
                                                   value="{{ $permission->id }}"
                                                   class="mt-1 mr-3 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-400 rounded">
                                            <div class="flex-1">
                                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $permission->title_ru }}</span>
                                                @if($permission->description_ru)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($permission->description_ru, 50) }}</p>
                                                @endif
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Выбрано: <span class="font-semibold" x-text="$wire.rolePermissions.length">0</span> прав
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 dark:bg-blue-500 text-base font-medium text-white hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Обновить
                        </button>
                        <button type="button"
                                wire:click="closeEditRoleModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Create Permission Modal -->
    @if($showCreatePermissionModal)
    <div wire:ignore.self class="fixed inset-0 overflow-y-auto" style="z-index: 9999;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="closeCreatePermissionModal" style="z-index: 9998;">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-200 dark:border-gray-700 relative" style="z-index: 9999;">
                <form wire:submit.prevent="createPermission">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Создание права доступа</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Titles -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Название (RU)*
                                    </label>
                                    <input type="text"
                                           wire:model="permissionTitleRu"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('permissionTitleRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Название (KK)
                                    </label>
                                    <input type="text"
                                           wire:model="permissionTitleKk"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('permissionTitleKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Название (EN)
                                    </label>
                                    <input type="text"
                                           wire:model="permissionTitleEn"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('permissionTitleEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Descriptions -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (RU)
                                    </label>
                                    <textarea wire:model="permissionDescriptionRu"
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                    @error('permissionDescriptionRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (KK)
                                    </label>
                                    <textarea wire:model="permissionDescriptionKk"
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                    @error('permissionDescriptionKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (EN)
                                    </label>
                                    <textarea wire:model="permissionDescriptionEn"
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                    @error('permissionDescriptionEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Value and Checkbox -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Значение (уникальное)*
                                    </label>
                                    <input type="text"
                                           wire:model="permissionValue"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('permissionValue') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex items-center pt-6">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="permissionIsSystem" class="mr-2 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-400">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Системное право</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 dark:bg-blue-500 text-base font-medium text-white hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Создать
                        </button>
                        <button type="button"
                                wire:click="closeCreatePermissionModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Permission Modal -->
    @if($showEditPermissionModal)
    <div wire:ignore.self class="fixed inset-0 overflow-y-auto" style="z-index: 9999;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="closeEditPermissionModal" style="z-index: 9998;">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-200 dark:border-gray-700 relative" style="z-index: 9999;">
                <form wire:submit.prevent="updatePermission">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Редактирование права доступа</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Titles -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Название (RU)*
                                    </label>
                                    <input type="text"
                                           wire:model="permissionTitleRu"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('permissionTitleRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Название (KK)
                                    </label>
                                    <input type="text"
                                           wire:model="permissionTitleKk"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('permissionTitleKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Название (EN)
                                    </label>
                                    <input type="text"
                                           wire:model="permissionTitleEn"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('permissionTitleEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Descriptions -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (RU)
                                    </label>
                                    <textarea wire:model="permissionDescriptionRu"
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                    @error('permissionDescriptionRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (KK)
                                    </label>
                                    <textarea wire:model="permissionDescriptionKk"
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                    @error('permissionDescriptionKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (EN)
                                    </label>
                                    <textarea wire:model="permissionDescriptionEn"
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                    @error('permissionDescriptionEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Value and Checkbox -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Значение (уникальное)*
                                    </label>
                                    <input type="text"
                                           wire:model="permissionValue"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('permissionValue') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex items-center pt-6">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="permissionIsSystem" class="mr-2 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-400">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Системное право</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 dark:bg-blue-500 text-base font-medium text-white hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Обновить
                        </button>
                        <button type="button"
                                wire:click="closeEditPermissionModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>