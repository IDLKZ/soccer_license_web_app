<div>
    @if($application)
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                        Детали заявки
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Полная информация о заявке на лицензирование
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="{{ $this->getApplicationStatusColor($application->application_status_category->value) }} px-4 py-2 rounded-full text-sm font-medium inline-flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ $application->application_status_category->title_ru }}
                    </span>
                </div>
            </div>

            <!-- Application Info Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- License Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-lg mr-4">
                            <i class="fas fa-certificate text-purple-600 dark:text-purple-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Лицензия</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $licence->title_ru }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Сезон</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $licence->season->title_ru ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Лига</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $licence->league->title_ru ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Период действия</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $licence->start_at->format('d.m.Y') }} - {{ $licence->end_at->format('d.m.Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('club.licence-detail', $licence->id) }}" class="text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 text-sm font-medium inline-flex items-center">
                            <i class="fas fa-arrow-right mr-2"></i>
                            Подробнее об лицензии
                        </a>
                    </div>
                </div>

                <!-- Club Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 dark:bg-green-900 p-3 rounded-lg mr-4">
                            <i class="fas fa-building text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Клуб</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $club->short_name_ru ?? $club->full_name_ru }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Полное название</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $club->full_name_ru }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">БИН</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $club->bin }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Дата основания</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $club->foundation_date->format('d.m.Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- User Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg mr-4">
                            <i class="fas fa-user text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Ответственный</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->role->title_ru ?? 'Пользователь' }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">ФИО</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Email</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->email }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Телефон</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->phone_number ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        @if(!empty($criteriaTabs))
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
            <!-- Tab Headers -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex -mb-px overflow-x-auto">
                    @foreach($criteriaTabs as $tab)
                        <button
                            wire:click="setActiveTab('{{ $tab['category']->id }}')"
                            class="{{ $activeTab === $tab['category']->id ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}
                             flex items-center px-6 py-4 border-b-2 font-medium text-sm transition-colors whitespace-nowrap"
                        >
                            <i class="fas fa-folder mr-2"></i>
                            {{ $tab['title'] }}
                            <span class="ml-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded-full text-xs">
                                {{ $tab['criteria']->count() }}
                            </span>
                        </button>
                    @endforeach
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                @if($activeTab)
                    @php
                        $activeCategory = collect($criteriaTabs)->firstWhere('category.id', $activeTab);
                    @endphp

                    @if($activeCategory)
                        <!-- Criteria Overview -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                {{ $activeCategory['title'] }} - Критерии
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($activeCategory['criteria'] as $criterion)
                                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $criterion->category_document->title_ru }}
                                            </h4>
                                            <span class="{{ $this->getCriterionStatusColor($criterion) }} px-2 py-1 rounded text-xs font-medium">
                                                {{ $criterion->is_ready ? 'Готово' : 'Не готово' }}
                                            </span>
                                        </div>

                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Статус: {{ $criterion->application_status->title_ru ?? 'Не определен' }}
                                        </div>

                                        @if($this->canUploadDocuments($criterion))
                                            <button
                                                wire:click="openUploadModal({{ $criterion->id }})"
                                                class="mt-3 text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 text-xs font-medium inline-flex items-center">
                                                <i class="fas fa-upload mr-1"></i>
                                                Загрузить документы
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Documents by Requirements -->
                        @if(!empty($licenceRequirementsByCategory))
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                    Документы по требованиям
                                </h3>

                                @foreach($licenceRequirementsByCategory as $documentId => $data)
                                    @php
                                        $document = $data['document'];
                                        $applicationDocuments = $this->getDocumentsForRequirement($data['requirements'][0] ?? null);
                                    @endphp

                                    @if($document)
                                    <div class="mb-6">
                                        <div class="flex items-center mb-3">
                                            <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">
                                                {{ $document->title_ru }}
                                            </h4>
                                            <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                                                ({{ $applicationDocuments->count() }} документов)
                                            </span>
                                        </div>

                                        <!-- Requirements for this document -->
                                        <div class="space-y-3 ml-6">
                                            @foreach($data['requirements'] as $requirementData)
                                                @php
                                                    $requirement = (object) $requirementData;
                                                @endphp
                                                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                                    <div class="flex items-center justify-between mb-3">
                                                        <div>
                                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                {{ $requirement->is_required ? 'Обязательно' : 'Необязательно' }}
                                                            </span>
                                                            @if($requirement->max_file_size_mb)
                                                                <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                                                                    Макс. размер: {{ $requirement->max_file_size_mb }} МБ
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Uploaded Documents -->
                                                    <div class="space-y-2">
                                                        @foreach($applicationDocuments as $appDoc)
                                                            <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-700">
                                                                <div class="flex items-center">
                                                                    <i class="fas fa-file text-gray-400 mr-2"></i>
                                                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                                                        {{ $appDoc->pivot->title ?? $appDoc->title_ru ?? 'Документ' }}
                                                                    </span>
                                                                </div>
                                                                <div class="flex items-center space-x-2">
                                                                    @if($appDoc->pivot->file_url)
                                                                        <a href="{{ $appDoc->pivot->file_url }}" target="_blank" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                                                            <i class="fas fa-download"></i>
                                                                        </a>
                                                                    @endif
                                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                                        {{ $appDoc->pivot->created_at->format('d.m.Y H:i') }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                        @if($applicationDocuments->isEmpty())
                                                            <div class="text-center py-4 text-gray-500 dark:text-gray-400 text-sm">
                                                                Документы еще не загружены
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </div>

        <!-- Upload Modal -->
        @if($showUploadModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full mx-4">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Загрузка документов
                            </h3>
                            <button wire:click="closeUploadModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        @if($selectedCriterion)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Загрузка документов для критерия:
                                    <span class="font-medium">{{ $selectedCriterion->category_document->title_ru }}</span>
                                </p>
                            </div>
                        @endif

                        <div class="flex justify-end space-x-3">
                            <button wire:click="closeUploadModal" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                Отмена
                            </button>
                            <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                Загрузить
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @else
        <div class="text-center py-12">
            <div class="bg-gray-100 dark:bg-gray-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                Нет доступных критериев
            </h3>
            <p class="text-gray-500 dark:text-gray-400">
                У вас нет прав для просмотра критериев этой заявки.
            </p>
        </div>
        @endif
    @else
    <div class="text-center py-12">
        <div class="bg-gray-100 dark:bg-gray-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-search text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
            Заявка не найдена
        </h3>
        <p class="text-gray-500 dark:text-gray-400">
            Запрошенная заявка не существует или у вас нет прав для ее просмотра.
        </p>
        <div class="mt-6">
            <a href="{{ route('club.applications') }}" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Вернуться к заявкам
            </a>
        </div>
    </div>
    @endif
</div>