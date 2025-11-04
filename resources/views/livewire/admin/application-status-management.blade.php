<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Статусы заявок</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Управление статусами заявок на лицензирование</p>
        </div>
        @if($canCreate)
        <button wire:click="$set('showCreateModal', true)"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors duration-150">
            <i class="fas fa-plus mr-2"></i>
            Создать статус
        </button>
        @endif
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

    <!-- Search and Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-search mr-1 text-gray-400 dark:text-gray-500"></i>
                    Поиск
                </label>
                <input type="text"
                       wire:model.live.debounce.500ms="search"
                       placeholder="Название или значение..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-layer-group mr-1 text-gray-400 dark:text-gray-500"></i>
                    Категория
                </label>
                <select wire:model.live="filterCategory"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                    <option value="">Все</option>
                    @foreach($availableCategories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->title_ru }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-filter mr-1 text-gray-400 dark:text-gray-500"></i>
                    Результат
                </label>
                <select wire:model.live="filterResult"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                    <option value="">Все</option>
                    <option value="1">Одобрено (1)</option>
                    <option value="0">В процессе (0)</option>
                    <option value="-1">Отклонено (-1)</option>
                </select>
            </div>
        </div>
    </div>

    @if($statuses->count() > 0)
    <!-- Statuses Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg dark:shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-hashtag mr-1 text-gray-400 dark:text-gray-500"></i>
                                ID
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-tag mr-1 text-gray-400 dark:text-gray-500"></i>
                                Название
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-layer-group mr-1 text-gray-400 dark:text-gray-500"></i>
                                Категория
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-users mr-1 text-gray-400 dark:text-gray-500"></i>
                                Роли
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-flag mr-1 text-gray-400 dark:text-gray-500"></i>
                                Флаги
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-chart-line mr-1 text-gray-400 dark:text-gray-500"></i>
                                Результат
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-cog mr-1 text-gray-400 dark:text-gray-500"></i>
                                Действия
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($statuses as $status)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $status->id }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $status->title_ru }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $status->title_kk }}</div>
                            </td>
                            <td class="px-4 py-4">
                                @if($status->application_status_category)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200">
                                        {{ $status->application_status_category->title_ru }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500 italic">Нет категории</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if(!empty($status->role_values))
                                    @php
                                        $statusRoleValues = $status->role_values;
                                        if (is_string($statusRoleValues)) {
                                            $statusRoleValues = json_decode($statusRoleValues, true) ?? [];
                                        }
                                    @endphp
                                    <div class="flex flex-wrap gap-1 justify-center">
                                        @foreach($statusRoleValues as $roleValue)
                                            @php
                                                $role = $availableRoles->firstWhere('value', $roleValue);
                                            @endphp
                                            @if($role)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200">
                                                    {{ $role->title_ru }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center">
                                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">Нет ролей</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-col items-center gap-1">
                                    @if($status->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200">
                                            <i class="fas fa-check-circle mr-1"></i>Активен
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-400">
                                            <i class="fas fa-times-circle mr-1"></i>Неактивен
                                        </span>
                                    @endif
                                    @if($status->is_first)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">
                                            <i class="fas fa-play mr-1"></i>Первый
                                        </span>
                                    @endif
                                    @if($status->is_last)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200">
                                            <i class="fas fa-stop mr-1"></i>Последний
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                @if($status->result === 1)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-400 to-green-600 text-white shadow-sm">
                                        <i class="fas fa-check mr-1"></i> Одобрено
                                    </span>
                                @elseif($status->result === -1)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-400 to-red-600 text-white shadow-sm">
                                        <i class="fas fa-times mr-1"></i> Отклонено
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-yellow-400 to-yellow-600 text-white shadow-sm">
                                        <i class="fas fa-clock mr-1"></i> В процессе
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center gap-2">
                                    @if($canEdit)
                                        <button wire:click="editStatus({{ $status->id }})"
                                                class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                                title="Редактировать">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif
                                    @if($canDelete)
                                        <button wire:click="deleteStatus({{ $status->id }})"
                                                onclick="return confirm('Вы уверены, что хотите удалить этот статус?')"
                                                class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                                title="Удалить">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
            {{ $statuses->links() }}
        </div>
    </div>
    @else
    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="flex flex-col items-center">
            <i class="fas fa-list text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Статусы не найдены</p>
            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Попробуйте изменить параметры фильтрации</p>
        </div>
    </div>
    @endif

    <!-- Create Modal -->
    @if($showCreateModal)
    <div wire:ignore.self class="fixed inset-0 overflow-y-auto" style="z-index: 9999;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="closeCreateModal" style="z-index: 9998;">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-200 dark:border-gray-700 relative" style="z-index: 9999;">
                <form wire:submit.prevent="createStatus">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Создание статуса</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Multi-language Titles -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название (RU)*</label>
                                    <input type="text" wire:model="titleRu" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название (KK)*</label>
                                    <input type="text" wire:model="titleKk" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название (EN)</label>
                                    <input type="text" wire:model="titleEn"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Multi-language Descriptions -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание (RU)</label>
                                    <textarea wire:model="descriptionRu" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание (KK)</label>
                                    <textarea wire:model="descriptionKk" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание (EN)</label>
                                    <textarea wire:model="descriptionEn" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                </div>
                            </div>

                            <!-- References -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Категория</label>
                                    <select wire:model="categoryId"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="">Нет</option>
                                        @foreach($availableCategories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->title_ru }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Предыдущий статус</label>
                                    <select wire:model="previousId"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="">Нет</option>
                                        @foreach($availableStatuses as $st)
                                            <option value="{{ $st->id }}">{{ $st->title_ru }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Следующий статус</label>
                                    <select wire:model="nextId"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="">Нет</option>
                                        @foreach($availableStatuses as $st)
                                            <option value="{{ $st->id }}">{{ $st->title_ru }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Roles -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Роли с доступом</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 max-h-60 overflow-y-auto">
                                    @foreach($availableRoles as $role)
                                        <label class="flex items-center space-x-2 cursor-pointer hover:bg-white dark:hover:bg-gray-800 p-2 rounded transition-colors">
                                            <input type="checkbox" wire:model="selectedRoles" value="{{ $role->id }}"
                                                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-700 transition-colors">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $role->title_ru }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Flags and Result -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="flex items-center">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" wire:model="isActive"
                                               class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-700 transition-colors">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Активен</span>
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" wire:model="isFirst"
                                               class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-700 transition-colors">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Первый</span>
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" wire:model="isLast"
                                               class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-700 transition-colors">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Последний</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Результат*</label>
                                    <select wire:model="result"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="1">Одобрено (1)</option>
                                        <option value="0">В процессе (0)</option>
                                        <option value="-1">Отклонено (-1)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            <i class="fas fa-check mr-2"></i>
                            Создать
                        </button>
                        <button type="button" wire:click="closeCreateModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Modal - Same structure as Create but with editStatus and updateStatus -->
    @if($showEditModal)
    <div wire:ignore.self class="fixed inset-0 overflow-y-auto" style="z-index: 9999;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="closeEditModal" style="z-index: 9998;">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-200 dark:border-gray-700 relative" style="z-index: 9999;">
                <form wire:submit.prevent="updateStatus">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Редактирование статуса</h3>
                        </div>

                        <!-- Same form fields as create modal -->
                        <div class="grid grid-cols-1 gap-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название (RU)*</label>
                                    <input type="text" wire:model="titleRu" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название (KK)*</label>
                                    <input type="text" wire:model="titleKk" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название (EN)</label>
                                    <input type="text" wire:model="titleEn"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание (RU)</label>
                                    <textarea wire:model="descriptionRu" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание (KK)</label>
                                    <textarea wire:model="descriptionKk" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание (EN)</label>
                                    <textarea wire:model="descriptionEn" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"></textarea>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Категория</label>
                                    <select wire:model="categoryId"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="">Нет</option>
                                        @foreach($availableCategories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->title_ru }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Предыдущий статус</label>
                                    <select wire:model="previousId"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="">Нет</option>
                                        @foreach($availableStatuses as $st)
                                            @if($st->id !== $editingStatusId)
                                                <option value="{{ $st->id }}">{{ $st->title_ru }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Следующий статус</label>
                                    <select wire:model="nextId"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="">Нет</option>
                                        @foreach($availableStatuses as $st)
                                            @if($st->id !== $editingStatusId)
                                                <option value="{{ $st->id }}">{{ $st->title_ru }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Роли с доступом</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 max-h-60 overflow-y-auto">
                                    @foreach($availableRoles as $role)
                                        <label class="flex items-center space-x-2 cursor-pointer hover:bg-white dark:hover:bg-gray-800 p-2 rounded transition-colors">
                                            <input type="checkbox" wire:model="selectedRoles" value="{{ $role->id }}"
                                                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-700 transition-colors">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $role->title_ru }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="flex items-center">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" wire:model="isActive"
                                               class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-700 transition-colors">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Активен</span>
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" wire:model="isFirst"
                                               class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-700 transition-colors">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Первый</span>
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" wire:model="isLast"
                                               class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:bg-gray-700 transition-colors">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Последний</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Результат*</label>
                                    <select wire:model="result"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="1">Одобрено (1)</option>
                                        <option value="0">В процессе (0)</option>
                                        <option value="-1">Отклонено (-1)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Сохранить
                        </button>
                        <button type="button" wire:click="closeEditModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
