<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Управление составом команд</h1>
        <p class="text-gray-600 dark:text-gray-400">Управление участниками команд клубов</p>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-6 border-l-4 border-green-500 bg-green-50 dark:bg-green-900/20 p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 dark:text-green-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Filters & Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6 p-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Поиск
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <input
                        type="text"
                        id="search"
                        wire:model.live.debounce.500ms="search"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400"
                        placeholder="Поиск по имени, email или клубу..."
                    >
                </div>
            </div>

            <!-- Filter by Club -->
            <div>
                <label for="filterClub" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Клуб
                </label>
                <select
                    id="filterClub"
                    wire:model.live="filterClub"
                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400"
                >
                    <option value="">Все клубы</option>
                    @foreach($clubs as $club)
                        <option value="{{ $club->id }}">{{ $club->short_name_ru }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Create Button -->
            <div class="flex items-end">
                @if($canCreate)
                    <button
                        wire:click="$set('showCreateModal', true)"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transition-all duration-200 flex items-center justify-center"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        Добавить
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Клуб
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Участник
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Роль
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Дата добавления
                        </th>
                        @if($canEdit || $canDelete)
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Действия
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($teams as $team)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-500 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-futbol text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $team->club->short_name_ru }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $team->club->short_name_kk }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $team->user->first_name }} {{ $team->user->last_name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $team->user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($team->role)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 text-indigo-800 dark:text-indigo-200">
                                        <i class="fas fa-shield-alt mr-1.5"></i>
                                        {{ $team->role->title_ru }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                {{ $team->created_at->format('d.m.Y H:i') }}
                            </td>
                            @if($canEdit || $canDelete)
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($canEdit)
                                            <button
                                                wire:click="editTeam({{ $team->id }})"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                                title="Редактировать"
                                            >
                                                <i class="fas fa-edit text-lg"></i>
                                            </button>
                                        @endif
                                        @if($canDelete)
                                            <button
                                                wire:click="deleteTeam({{ $team->id }})"
                                                wire:confirm="Вы уверены, что хотите удалить этого участника из команды?"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                                title="Удалить"
                                            >
                                                <i class="fas fa-trash text-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-users text-gray-400 dark:text-gray-600 text-5xl mb-4"></i>
                                    <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Участники не найдены</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Попробуйте изменить фильтры или добавить нового участника</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $teams->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
    <div wire:ignore.self class="fixed inset-0 overflow-y-auto" style="z-index: 9999;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="closeCreateModal" style="z-index: 9998;">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700 relative" style="z-index: 9999;">
                <form wire:submit.prevent="createTeam">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                                <i class="fas fa-user-plus mr-2"></i>
                                Добавить участника
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <!-- Club -->
                            <div>
                                <label for="create-club" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Клуб <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="create-club"
                                    wire:model="clubId"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"
                                    required
                                >
                                    <option value="">Выберите клуб</option>
                                    @foreach($clubs as $club)
                                        <option value="{{ $club->id }}">{{ $club->short_name_ru }}</option>
                                    @endforeach
                                </select>
                                @error('clubId')
                                    <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- User -->
                            <div>
                                <label for="create-user" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Пользователь <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="create-user"
                                    wire:model="userId"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"
                                    required
                                >
                                    <option value="">Выберите пользователя</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                            @if($user->role)
                                                - {{ $user->role->title_ru }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('userId')
                                    <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                                <div class="flex">
                                    <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 mt-0.5 mr-2"></i>
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        Роль участника будет автоматически установлена из роли выбранного пользователя
                                    </p>
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
                                wire:click="closeCreateModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal)
    <div wire:ignore.self class="fixed inset-0 overflow-y-auto" style="z-index: 9999;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="closeEditModal" style="z-index: 9998;">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700 relative" style="z-index: 9999;">
                <form wire:submit.prevent="updateTeam">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center">
                                <i class="fas fa-edit mr-2"></i>
                                Редактировать участника
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <!-- Club -->
                            <div>
                                <label for="edit-club" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Клуб <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="edit-club"
                                    wire:model="clubId"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"
                                    required
                                >
                                    <option value="">Выберите клуб</option>
                                    @foreach($clubs as $club)
                                        <option value="{{ $club->id }}">{{ $club->short_name_ru }}</option>
                                    @endforeach
                                </select>
                                @error('clubId')
                                    <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- User -->
                            <div>
                                <label for="edit-user" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Пользователь <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="edit-user"
                                    wire:model="userId"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"
                                    required
                                >
                                    <option value="">Выберите пользователя</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                                            @if($user->role)
                                                - {{ $user->role->title_ru }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('userId')
                                    <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                                <div class="flex">
                                    <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 mt-0.5 mr-2"></i>
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        Роль участника будет автоматически обновлена из роли выбранного пользователя
                                    </p>
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
                                wire:click="closeEditModal"
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
