<div>
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 dark:bg-green-900 dark:border-green-600 dark:text-green-200 px-4 py-3 rounded-lg relative">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 dark:bg-red-900 dark:border-red-600 dark:text-red-200 px-4 py-3 rounded-lg relative">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($application)
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <div class="flex items-center space-x-4 mb-2">
                        <a href="{{ route('department.applications') }}" class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                            Детали заявки #{{ $application->id }}
                        </h1>
                    </div>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Просмотр заявки на лицензирование
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
                            class="{{ $activeTab === $tab['category']->id ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}
                             flex items-center px-6 py-4 border-b-2 font-medium text-sm transition-colors whitespace-nowrap"
                        >
                            <i class="fas fa-folder mr-2"></i>
                            {{ $tab['title'] }}
                            <span class="ml-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded-full text-xs">
                                {{ count($tab['criteria']) }}
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
                        $criterion = $activeCategory ? collect($activeCategory['criteria'])->first() : null;
                    @endphp

                    @if($activeCategory && $criterion)
                        <!-- Criterion Status Info (No action buttons for department) -->
                        <div class="mb-6 bg-gray-50 dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                        Статус критерия: {{ $activeCategory['title'] }}
                                    </h3>
                                    <div class="flex items-center space-x-4">
                                        <span class="{{ $this->getCriterionStatusColor($criterion) }} px-3 py-1 rounded-full text-xs font-medium">
                                            @if(!$criterion->is_ready)
                                                <i class="fas fa-clock mr-1"></i> Не готово
                                            @elseif($criterion->is_first_passed === false || $criterion->is_industry_passed === false || $criterion->is_final_passed === false)
                                                <i class="fas fa-exclamation-triangle mr-1"></i> Требует исправлений
                                            @elseif($criterion->is_first_passed === null || $criterion->is_industry_passed === null || $criterion->is_final_passed === null)
                                                <i class="fas fa-spinner mr-1"></i> На проверке
                                            @else
                                                <i class="fas fa-check-circle mr-1"></i> Принято
                                            @endif
                                        </span>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $criterion->application_status->title_ru ?? 'Не определен' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documents by Requirements (View only - no upload) -->
                        @if(!empty($licenceRequirementsByCategory))
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                    Требования к документам
                                </h3>

                                @foreach($licenceRequirementsByCategory as $documentId => $data)
                                    @php
                                        $document = $data['document'];
                                        $requirements = $data['requirements'];
                                        $requirement = collect($requirements)->first();
                                        $uploadedDocs = $this->getUploadedDocumentsForRequirement($documentId);
                                    @endphp

                                    @if($document && $requirement)
                                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                                        <!-- Document Header (No upload button for department) -->
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <i class="fas fa-file-alt text-indigo-500 mr-2"></i>
                                                    <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $document->title_ru }}
                                                    </h4>
                                                    @if($requirement['is_required'])
                                                        <span class="ml-2 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-1 rounded text-xs font-medium">
                                                            Обязательно
                                                        </span>
                                                    @else
                                                        <span class="ml-2 bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 px-2 py-1 rounded text-xs font-medium">
                                                            Необязательно
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($document->description_ru)
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 ml-6">
                                                        {{ $document->description_ru }}
                                                    </p>
                                                @endif
                                                <div class="flex items-center space-x-4 ml-6 mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                    @if(!empty($requirement['allowed_extensions']))
                                                        <span>
                                                            <i class="fas fa-file-code mr-1"></i>
                                                            Форматы: {{ is_array($requirement['allowed_extensions']) ? implode(', ', $requirement['allowed_extensions']) : $requirement['allowed_extensions'] }}
                                                        </span>
                                                    @endif
                                                    @if($requirement['max_file_size_mb'])
                                                        <span>
                                                            <i class="fas fa-weight mr-1"></i>
                                                            Макс. размер: {{ $requirement['max_file_size_mb'] }} МБ
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Uploaded Documents List (View and Download only) -->
                                        <div class="mt-4 space-y-2">
                                            @if(!empty($uploadedDocs))
                                                @foreach($uploadedDocs as $appDoc)
                                                    @php
                                                        $doc = is_array($appDoc) ? (object)$appDoc : $appDoc;
                                                    @endphp
                                                    <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                                        <div class="flex items-center flex-1">
                                                            <i class="fas fa-file text-gray-400 mr-3"></i>
                                                            <div class="flex-1">
                                                                <div class="flex items-center">
                                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                        {{ $doc->title ?? 'Документ' }}
                                                                    </span>
                                                                    @if($doc->is_first_passed === false)
                                                                        <span class="ml-2 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-1 rounded text-xs">
                                                                            <i class="fas fa-times mr-1"></i>Не прошел первичную
                                                                        </span>
                                                                    @elseif($doc->is_industry_passed === false)
                                                                        <span class="ml-2 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-1 rounded text-xs">
                                                                            <i class="fas fa-times mr-1"></i>Не прошел отраслевую
                                                                        </span>
                                                                    @elseif($doc->is_final_passed === false)
                                                                        <span class="ml-2 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-1 rounded text-xs">
                                                                            <i class="fas fa-times mr-1"></i>Не прошел контрольную
                                                                        </span>
                                                                    @elseif($doc->is_first_passed === null && $doc->is_industry_passed === null && $doc->is_final_passed === null)
                                                                        <span class="ml-2 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-2 py-1 rounded text-xs">
                                                                            <i class="fas fa-clock mr-1"></i>Ожидает проверки
                                                                        </span>
                                                                    @elseif($doc->is_first_passed === true && $doc->is_industry_passed === true && $doc->is_final_passed === true)
                                                                        <span class="ml-2 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-2 py-1 rounded text-xs">
                                                                            <i class="fas fa-check mr-1"></i>Принято
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                @if($doc->info)
                                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $doc->info }}</p>
                                                                @endif
                                                                <div class="flex items-center space-x-3 mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                                    <span>
                                                                        <i class="fas fa-user mr-1"></i>{{ $doc->uploaded_by ?? 'Неизвестно' }}
                                                                    </span>
                                                                    <span>
                                                                        <i class="fas fa-clock mr-1"></i>{{ isset($doc->created_at) ? (is_string($doc->created_at) ? $doc->created_at : $doc->created_at->format('d.m.Y H:i')) : '-' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Only view/download button for department users -->
                                                        <div class="flex items-center space-x-2 ml-4">
                                                            @if($doc->file_url)
                                                                <a href="{{ Storage::url($doc->file_url) }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 p-2" title="Просмотр/Скачать">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-center py-6 text-gray-500 dark:text-gray-400 text-sm">
                                                    <i class="fas fa-inbox text-3xl mb-2"></i>
                                                    <p>Документы еще не загружены</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="bg-gray-100 dark:bg-gray-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-folder-open text-gray-400 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    Нет требований
                                </h3>
                                <p class="text-gray-500 dark:text-gray-400">
                                    Для этой категории не определены требования к документам.
                                </p>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </div>

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
            <a href="{{ route('department.applications') }}" class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Вернуться к заявкам
            </a>
        </div>
    </div>
    @endif
</div>
