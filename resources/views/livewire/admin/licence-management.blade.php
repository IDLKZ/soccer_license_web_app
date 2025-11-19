<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Управление лицензиями</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Создание и управление лицензиями с требованиями и дедлайнами</p>
        </div>
        @if($canCreate)
        <button wire:click="$set('showCreateModal', true)"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors duration-150">
            <i class="fas fa-plus mr-2"></i>
            Создать лицензию
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

    <!-- Поиск и фильтры -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-search mr-1 text-gray-400 dark:text-gray-500"></i>
                    Поиск
                </label>
                <input type="text"
                       wire:model.live.debounce.500ms="search"
                       placeholder="Название лицензии..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-calendar mr-1 text-gray-400 dark:text-gray-500"></i>
                    Сезон
                </label>
                <select wire:model.live="filterSeasonId"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                    <option value="">Все сезоны</option>
                    @foreach($seasons as $season)
                    <option value="{{ $season->id }}">{{ $season->title_ru }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-trophy mr-1 text-gray-400 dark:text-gray-500"></i>
                    Соревнования
                </label>
                <select wire:model.live="filterLeagueId"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                    <option value="">Все соревнования</option>
                    @foreach($leagues as $league)
                    <option value="{{ $league->id }}">{{ $league->title_ru }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if($licences->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg dark:shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-certificate mr-1 text-gray-400 dark:text-gray-500"></i>
                                Лицензия
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-1 text-gray-400 dark:text-gray-500"></i>
                                Сезон / Соревнование
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-1 text-gray-400 dark:text-gray-500"></i>
                                Период
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-list-check mr-1 text-gray-400 dark:text-gray-500"></i>
                                Требования
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
                    @foreach($licences as $licence)
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/30 transition-all duration-150 border-l-4 border-transparent hover:border-blue-400 dark:hover:border-blue-500">
                        <td class="px-4 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center">
                                    <i class="fas fa-certificate text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $licence->title_ru }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $licence->title_kk }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                @if($licence->season)
                                <i class="fas fa-calendar text-green-500 dark:text-green-400 mr-1"></i>
                                {{ $licence->season?->title_ru ?? 'Неизвестно' }}
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                @if($licence->league)
                                <i class="fas fa-trophy text-yellow-500 dark:text-yellow-400 mr-1"></i>
                                {{ $licence->league?->title_ru ?? 'Неизвестно' }}
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                {{ $licence->start_at?->format('d.m.Y') ?? '—' }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                до {{ $licence->end_at?->format('d.m.Y') ?? '—' }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-700">
                                <i class="fas fa-list-check mr-1 text-blue-600 dark:text-blue-400"></i>
                                {{ $licence->licence_requirements->count() }} треб.
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            @if($licence->is_active)
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
                                @if($canEdit)
                                <button wire:click="editLicence({{ $licence->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/30 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-150"
                                        title="Редактировать">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                @endif
                                @if($canDelete)
                                <button wire:click="deleteLicence({{ $licence->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150"
                                        title="Удалить"
                                        onclick="return confirm('Вы уверены, что хотите удалить эту лицензию?')">
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
    @if($licences->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $licences->links('pagination::livewire-tailwind') }}
    </div>
    @endif
    @else
    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="flex flex-col items-center">
            <i class="fas fa-certificate text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Лицензии не найдены</p>
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

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full border border-gray-200 dark:border-gray-700 relative" style="z-index: 9999;">
                <form wire:submit.prevent="createLicence">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Создание лицензии</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Basic Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Сезон</label>
                                    <select wire:model="seasonId"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="">Выберите сезон</option>
                                        @foreach($seasons as $season)
                                        <option value="{{ $season->id }}">{{ $season->title_ru }}</option>
                                        @endforeach
                                    </select>
                                    @error('seasonId') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Соревнование</label>
                                    <select wire:model="leagueId"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="">Выберите соревнование</option>
                                        @foreach($leagues as $league)
                                        <option value="{{ $league->id }}">{{ $league->title_ru }}</option>
                                        @endforeach
                                    </select>
                                    @error('leagueId') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Titles -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название (RU)*</label>
                                    <input type="text" wire:model="titleRu"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Атауы (KK)*</label>
                                    <input type="text" wire:model="titleKk"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title (EN)</label>
                                    <input type="text" wire:model="titleEn"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Дата начала*</label>
                                    <input type="date" wire:model="startAt"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('startAt') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Дата окончания*</label>
                                    <input type="date" wire:model="endAt"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('endAt') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Статус</label>
                                    <select wire:model="isActive"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="1">Активна</option>
                                        <option value="0">Неактивна</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Requirements Section -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                        Требования к документам
                                        <span class="text-red-500 ml-1">*</span>
                                    </h4>
                                    <button type="button" wire:click="addRequirement"
                                            class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors">
                                        <i class="fas fa-plus mr-1"></i> Добавить
                                    </button>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Необходимо добавить хотя бы одно требование к лицензии</p>

                                @error('requirements')
                                    <p class="text-sm text-red-500 dark:text-red-400 mb-3">{{ $message }}</p>
                                @enderror

                                @foreach($requirements as $index => $requirement)
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg mb-3 relative">
                                    <button type="button" wire:click="removeRequirement({{ $index }})"
                                            class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Категория документа</label>
                                            <select wire:model.live="requirements.{{ $index }}.category_id"
                                                    class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                                <option value="">Выберите категорию</option>
                                                @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->title_ru }}</option>
                                                @endforeach
                                            </select>
                                            @error("requirements.{$index}.category_id")
                                                <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Документ</label>
                                            <select wire:model="requirements.{{ $index }}.document_id"
                                                    class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md"
                                                    @if(empty($requirement['category_id'])) disabled @endif>
                                                <option value="">{{ empty($requirement['category_id']) ? 'Сначала выберите категорию' : 'Выберите документ' }}</option>
                                                @if(!empty($requirement['category_id']))
                                                    @foreach($this->getDocumentsByCategory($requirement['category_id']) as $document)
                                                    <option value="{{ $document->id }}">{{ $document->title_ru }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error("requirements.{$index}.document_id")
                                                <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Макс. размер (МБ)</label>
                                            <input type="number" step="0.1" wire:model="requirements.{{ $index }}.max_file_size_mb"
                                                   class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Обязательность</label>
                                            <select wire:model="requirements.{{ $index }}.is_required"
                                                    class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                                <option value="1">Обязательный</option>
                                                <option value="0">Необязательный</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Разрешенные расширения файлов</label>
                                        <div class="flex flex-wrap gap-2">
                                            @php
                                                $availableExtensions = [
                                                    // Documents
                                                    'pdf', 'doc', 'docx', 'odt', 'rtf', 'txt',
                                                    // Spreadsheets
                                                    'xls', 'xlsx', 'ods', 'csv',
                                                    // Images
                                                    'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg',
                                                    // Archives
                                                    'zip', 'rar', '7z', 'tar', 'gz',
                                                    // Presentations
                                                    'ppt', 'pptx', 'odp',
                                                    // Other
                                                    'xml', 'json', 'html'
                                                ];
                                                $currentExtensions = $requirement['allowed_extensions'] ?? [];
                                                // Ensure it's an array (could be string from DB)
                                                if (is_string($currentExtensions)) {
                                                    $currentExtensions = json_decode($currentExtensions, true) ?? [];
                                                }
                                                if (!is_array($currentExtensions)) {
                                                    $currentExtensions = [];
                                                }
                                            @endphp
                                            @foreach($availableExtensions as $ext)
                                                <label class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border cursor-pointer transition-colors
                                                    {{ in_array($ext, $currentExtensions)
                                                        ? 'bg-blue-100 dark:bg-blue-900/30 border-blue-500 dark:border-blue-400 text-blue-700 dark:text-blue-300'
                                                        : 'bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                                    <input type="checkbox"
                                                           value="{{ $ext }}"
                                                           wire:model.live="requirements.{{ $index }}.allowed_extensions"
                                                           class="sr-only">
                                                    <span>.{{ $ext }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                            <!-- Additional Add Button at Bottom -->
                            <div class="mt-4 flex justify-center">
                                <button type="button" wire:click="addRequirement"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-150 flex items-center">
                                    <i class="fas fa-plus mr-2"></i>
                                    Добавить еще требование
                                </button>
                            </div>
                            </div>

                            <!-- Deadlines Section (Mandatory) -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                        Дедлайны для клубов
                                        <span class="text-red-500 ml-1">*</span>
                                    </h4>
                                    <button type="button" wire:click="addDeadline"
                                            class="px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition-colors">
                                        <i class="fas fa-plus mr-1"></i> Добавить
                                    </button>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Необходимо добавить хотя бы один дедлайн для клуба</p>

                                @error('deadlines')
                                    <p class="text-sm text-red-500 dark:text-red-400 mb-3">{{ $message }}</p>
                                @enderror

                                @foreach($deadlines as $index => $deadline)
                                <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg mb-3 relative">
                                    <button type="button" wire:click="removeDeadline({{ $index }})"
                                            class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Клуб</label>
                                            <select wire:model="deadlines.{{ $index }}.club_id"
                                                    class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                                <option value="">Выберите клуб</option>
                                                @foreach($clubs as $club)
                                                <option value="{{ $club->id }}">{{ $club->short_name_ru }}</option>
                                                @endforeach
                                            </select>
                                            @error("deadlines.{$index}.club_id")
                                                <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Начало</label>
                                            <input type="date" wire:model="deadlines.{{ $index }}.start_at"
                                                   class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                            @error("deadlines.{$index}.start_at")
                                                <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Окончание</label>
                                            <input type="date" wire:model="deadlines.{{ $index }}.end_at"
                                                   class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                            @error("deadlines.{$index}.end_at")
                                                <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @endforeach
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

    <!-- Edit Modal - Similar structure to Create Modal -->
    @if($showEditModal)
    <div wire:ignore.self class="fixed inset-0 overflow-y-auto" style="z-index: 9999;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="closeEditModal" style="z-index: 9998;">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full border border-gray-200 dark:border-gray-700 relative" style="z-index: 9999;">
                <form wire:submit.prevent="updateLicence">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Редактирование лицензии</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Same form fields as Create Modal -->
                            <!-- Basic Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Сезон</label>
                                    <select wire:model="seasonId"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="">Выберите сезон</option>
                                        @foreach($seasons as $season)
                                        <option value="{{ $season->id }}">{{ $season->title_ru }}</option>
                                        @endforeach
                                    </select>
                                    @error('seasonId') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Соревнование</label>
                                    <select wire:model="leagueId"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="">Выберите соревнование</option>
                                        @foreach($leagues as $league)
                                        <option value="{{ $league->id }}">{{ $league->title_ru }}</option>
                                        @endforeach
                                    </select>
                                    @error('leagueId') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Titles -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название (RU)*</label>
                                    <input type="text" wire:model="titleRu"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Атауы (KK)*</label>
                                    <input type="text" wire:model="titleKk"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title (EN)</label>
                                    <input type="text" wire:model="titleEn"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Дата начала*</label>
                                    <input type="date" wire:model="startAt"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('startAt') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Дата окончания*</label>
                                    <input type="date" wire:model="endAt"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('endAt') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Статус</label>
                                    <select wire:model="isActive"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="1">Активна</option>
                                        <option value="0">Неактивна</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Requirements Section -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                        Требования к документам
                                        <span class="text-red-500 ml-1">*</span>
                                    </h4>
                                    <button type="button" wire:click="addRequirement"
                                            class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors">
                                        <i class="fas fa-plus mr-1"></i> Добавить
                                    </button>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Необходимо добавить хотя бы одно требование к лицензии</p>

                                @error('requirements')
                                    <p class="text-sm text-red-500 dark:text-red-400 mb-3">{{ $message }}</p>
                                @enderror

                                @foreach($requirements as $index => $requirement)
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg mb-3 relative">
                                    <button type="button" wire:click="removeRequirement({{ $index }})"
                                            class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Категория документа</label>
                                            <select wire:model.live="requirements.{{ $index }}.category_id"
                                                    class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                                <option value="">Выберите категорию</option>
                                                @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->title_ru }}</option>
                                                @endforeach
                                            </select>
                                            @error("requirements.{$index}.category_id")
                                                <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Документ</label>
                                            <select wire:model="requirements.{{ $index }}.document_id"
                                                    class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md"
                                                    @if(empty($requirement['category_id'])) disabled @endif>
                                                <option value="">{{ empty($requirement['category_id']) ? 'Сначала выберите категорию' : 'Выберите документ' }}</option>
                                                @if(!empty($requirement['category_id']))
                                                    @foreach($this->getDocumentsByCategory($requirement['category_id']) as $document)
                                                    <option value="{{ $document->id }}">{{ $document->title_ru }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error("requirements.{$index}.document_id")
                                                <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Макс. размер (МБ)</label>
                                            <input type="number" step="0.1" wire:model="requirements.{{ $index }}.max_file_size_mb"
                                                   class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Обязательность</label>
                                            <select wire:model="requirements.{{ $index }}.is_required"
                                                    class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                                <option value="1">Обязательный</option>
                                                <option value="0">Необязательный</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Разрешенные расширения файлов</label>
                                        <div class="flex flex-wrap gap-2">
                                            @php
                                                $availableExtensions = [
                                                    // Documents
                                                    'pdf', 'doc', 'docx', 'odt', 'rtf', 'txt',
                                                    // Spreadsheets
                                                    'xls', 'xlsx', 'ods', 'csv',
                                                    // Images
                                                    'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg',
                                                    // Archives
                                                    'zip', 'rar', '7z', 'tar', 'gz',
                                                    // Presentations
                                                    'ppt', 'pptx', 'odp',
                                                    // Other
                                                    'xml', 'json', 'html'
                                                ];
                                                $currentExtensions = $requirement['allowed_extensions'] ?? [];
                                                // Ensure it's an array (could be string from DB)
                                                if (is_string($currentExtensions)) {
                                                    $currentExtensions = json_decode($currentExtensions, true) ?? [];
                                                }
                                                if (!is_array($currentExtensions)) {
                                                    $currentExtensions = [];
                                                }
                                            @endphp
                                            @foreach($availableExtensions as $ext)
                                                <label class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border cursor-pointer transition-colors
                                                    {{ in_array($ext, $currentExtensions)
                                                        ? 'bg-blue-100 dark:bg-blue-900/30 border-blue-500 dark:border-blue-400 text-blue-700 dark:text-blue-300'
                                                        : 'bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                                    <input type="checkbox"
                                                           value="{{ $ext }}"
                                                           wire:model.live="requirements.{{ $index }}.allowed_extensions"
                                                           class="sr-only">
                                                    <span>.{{ $ext }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                            <!-- Additional Add Button at Bottom -->
                            <div class="mt-4 flex justify-center">
                                <button type="button" wire:click="addRequirement"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-150 flex items-center">
                                    <i class="fas fa-plus mr-2"></i>
                                    Добавить еще требование
                                </button>
                            </div>
                            </div>

                            <!-- Deadlines Section (Mandatory) -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                        Дедлайны для клубов
                                        <span class="text-red-500 ml-1">*</span>
                                    </h4>
                                    <button type="button" wire:click="addDeadline"
                                            class="px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition-colors">
                                        <i class="fas fa-plus mr-1"></i> Добавить
                                    </button>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Необходимо добавить хотя бы один дедлайн для клуба</p>

                                @error('deadlines')
                                    <p class="text-sm text-red-500 dark:text-red-400 mb-3">{{ $message }}</p>
                                @enderror

                                @foreach($deadlines as $index => $deadline)
                                <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg mb-3 relative">
                                    <button type="button" wire:click="removeDeadline({{ $index }})"
                                            class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Клуб</label>
                                            <select wire:model="deadlines.{{ $index }}.club_id"
                                                    class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                                <option value="">Выберите клуб</option>
                                                @foreach($clubs as $club)
                                                <option value="{{ $club->id }}">{{ $club->short_name_ru }}</option>
                                                @endforeach
                                            </select>
                                            @error("deadlines.{$index}.club_id")
                                                <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Начало</label>
                                            <input type="date" wire:model="deadlines.{{ $index }}.start_at"
                                                   class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                            @error("deadlines.{$index}.start_at")
                                                <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Окончание</label>
                                            <input type="date" wire:model="deadlines.{{ $index }}.end_at"
                                                   class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md">
                                            @error("deadlines.{$index}.end_at")
                                                <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @endforeach
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
