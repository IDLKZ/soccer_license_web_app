<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Управление дедлайнами лицензий</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Управление сроками подачи документов для клубов</p>
        </div>
        @if($canCreate)
        <button wire:click="$set('showCreateModal', true)"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors duration-150">
            <i class="fas fa-plus mr-2"></i>
            Создать дедлайн
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

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-search mr-1 text-gray-400 dark:text-gray-500"></i>
                    Поиск
                </label>
                <input type="text"
                       wire:model.live.debounce.500ms="search"
                       placeholder="Поиск по клубу или лицензии..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-certificate mr-1 text-gray-400 dark:text-gray-500"></i>
                    Лицензия
                </label>
                <select wire:model.live="filterLicence"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                    <option value="">Все лицензии</option>
                    @foreach($licences as $licence)
                    <option value="{{ $licence->id }}">{{ $licence->title_ru }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-futbol mr-1 text-gray-400 dark:text-gray-500"></i>
                    Клуб
                </label>
                <select wire:model.live="filterClub"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                    <option value="">Все клубы</option>
                    @foreach($clubs as $club)
                    <option value="{{ $club->id }}">{{ $club->short_name_ru }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if($deadlines->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg dark:shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Лицензия
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Клуб
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Период
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Статус
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Действия
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($deadlines as $deadline)
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/30 transition-all duration-150">
                        <td class="px-4 py-4">
                            @if($deadline->licence)
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                                    <i class="fas fa-certificate text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $deadline->licence?->title_ru ?? 'Неизвестно' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        @if($deadline->licence->season)
                                            {{ $deadline->licence?->season?->title_ru ?? '—' }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @if($deadline->club)
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center">
                                    <i class="fas fa-futbol text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $deadline->club?->short_name_ru ?? 'Неизвестно' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $deadline->club?->short_name_kk ?? '—' }}
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                <i class="fas fa-calendar-start text-green-500 mr-1"></i>
                                {{ $deadline->start_at?->format('d.m.Y') ?? '—' }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <i class="fas fa-calendar-xmark text-red-500 mr-1"></i>
                                {{ $deadline->end_at?->format('d.m.Y') ?? '—' }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            @php
                                $now = now();
                                $isActive = $deadline->start_at && $deadline->end_at && $now->between($deadline->start_at, $deadline->end_at);
                                $isPast = $deadline->end_at && $now->greaterThan($deadline->end_at);
                                $isFuture = $deadline->start_at && $now->lessThan($deadline->start_at);
                            @endphp
                            @if($isPast)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-gray-100 to-slate-100 dark:from-gray-700 dark:to-slate-700 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                <i class="fas fa-clock-rotate-left mr-1"></i>
                                Завершен
                            </span>
                            @elseif($isActive)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-700">
                                <i class="fas fa-circle-check mr-1"></i>
                                Активен
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-700">
                                <i class="fas fa-hourglass-start mr-1"></i>
                                Ожидает
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($canEdit)
                                <button wire:click="editDeadline({{ $deadline->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/30 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-150"
                                        title="Редактировать">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                @endif
                                @if($canDelete)
                                <button wire:click="deleteDeadline({{ $deadline->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150"
                                        title="Удалить"
                                        onclick="return confirm('Вы уверены, что хотите удалить этот дедлайн?')">
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

    <!-- Pagination -->
    @if($deadlines->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $deadlines->links('pagination::livewire-tailwind') }}
    </div>
    @endif
    @else
    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="flex flex-col items-center">
            <i class="fas fa-calendar-xmark text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Дедлайны не найдены</p>
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

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-200 dark:border-gray-700 relative" style="z-index: 9999;">
                <form wire:submit.prevent="createDeadline">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Создание дедлайна</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <!-- Licence -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Лицензия *</label>
                                <select wire:model="licenceId"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    <option value="">Выберите лицензию</option>
                                    @foreach($licences as $licence)
                                    <option value="{{ $licence->id }}">
                                        {{ $licence->title_ru }}
                                        @if($licence->season) - {{ $licence->season?->title_ru ?? 'Без сезона' }} @endif
                                        @if($licence->league) ({{ $licence->league?->title_ru ?? 'Без соревнования' }}) @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('licenceId') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Club -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Клуб *</label>
                                <select wire:model="clubId"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    <option value="">Выберите клуб</option>
                                    @foreach($clubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->short_name_ru }} - {{ $club->short_name_kk }}</option>
                                    @endforeach
                                </select>
                                @error('clubId') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Dates -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Дата начала *</label>
                                    <input type="date" wire:model="startAt"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('startAt') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Дата окончания *</label>
                                    <input type="date" wire:model="endAt"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('endAt') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
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

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-200 dark:border-gray-700 relative" style="z-index: 9999;">
                <form wire:submit.prevent="updateDeadline">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Редактирование дедлайна</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <!-- Licence -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Лицензия *</label>
                                <select wire:model="licenceId"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    <option value="">Выберите лицензию</option>
                                    @foreach($licences as $licence)
                                    <option value="{{ $licence->id }}">
                                        {{ $licence->title_ru }}
                                        @if($licence->season) - {{ $licence->season?->title_ru ?? 'Без сезона' }} @endif
                                        @if($licence->league) ({{ $licence->league?->title_ru ?? 'Без соревнования' }}) @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('licenceId') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Club -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Клуб *</label>
                                <select wire:model="clubId"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    <option value="">Выберите клуб</option>
                                    @foreach($clubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->short_name_ru }} - {{ $club->short_name_kk }}</option>
                                    @endforeach
                                </select>
                                @error('clubId') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Dates -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Дата начала *</label>
                                    <input type="date" wire:model="startAt"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('startAt') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Дата окончания *</label>
                                    <input type="date" wire:model="endAt"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('endAt') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
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
