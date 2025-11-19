<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                        <a href="{{ route('admin.applications') }}" class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                            Детали заявки #{{ $application->id }}
                        </h1>
                    </div>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Просмотр информации о заявке
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="{{ $this->getApplicationStatusColor($application->application_status_category?->value ?? 'draft') }} px-4 py-2 rounded-full text-sm font-medium inline-flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ $application->application_status_category?->title_ru ?? 'Не определен' }}
                    </span>
                </div>
            </div>

            <!-- Application Info Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- License Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-lg mr-4">
                            <i class="fas fa-certificate text-purple-600 dark:text-purple-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Лицензия</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $licence?->title_ru ?? 'Не указана' }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Сезон</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $licence?->season?->title_ru ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Соревнования</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $licence?->league?->title_ru ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Период действия</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $licence?->start_at?->format('d.m.Y') ?? '-' }} - {{ $licence?->end_at?->format('d.m.Y') ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Club Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 dark:bg-green-900 p-3 rounded-lg mr-4">
                            <i class="fas fa-building text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Клуб</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $club?->short_name_ru ?? $club?->full_name_ru ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Полное название</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 break-words">{{ $club?->full_name_ru ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">БИН</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $club?->bin ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Дата основания</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $club?->foundation_date?->format('d.m.Y') ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- User Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg mr-4">
                            <i class="fas fa-user text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Ответственный</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user?->role?->title_ru ?? 'Пользователь' }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">ФИО</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $user?->first_name ?? '' }} {{ $user?->last_name ?? '' }}
                            </p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Email</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user?->email ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Телефон</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user?->phone_number ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Tabs -->
        @if(!empty($criteriaTabs))
            <div class="mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
                        @foreach($criteriaTabs as $tab)
                            <button
                                wire:click="setActiveTab({{ $tab['category_id'] }})"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors
                                    {{ $activeTab == $tab['category_id']
                                        ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                                {{ $tab['title_ru'] }}
                            </button>
                        @endforeach
                    </nav>
                </div>
            </div>

            <!-- Criteria Content -->
            @foreach($criteriaTabs as $tab)
                @if($activeTab == $tab['category_id'])
                    <div class="space-y-6">
                        @foreach($tab['criteria'] as $criterion)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                                <!-- Criterion Header -->
                                <div class="flex items-start justify-between mb-6">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                            {{ $criterion->category_document?->title_ru ?? 'Неизвестная категория' }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $criterion->category_document?->description_ru ?? '' }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="{{ $this->getCriterionStatusColor($criterion->application_status?->value ?? 'draft') }} px-4 py-2 rounded-full text-sm font-medium inline-flex items-center">
                                            {{ $criterion->application_status?->title_ru ?? 'Не определен' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Documents by Requirements -->
                                @if(!empty($licenceRequirementsByCategory))
                                    <div class="space-y-6">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                            Требования к документам
                                        </h4>

                                        @foreach($licenceRequirementsByCategory as $documentId => $data)
                                            @php
                                                $document = $data['document'];
                                                $requirements = $data['requirements'];
                                                $requirement = collect($requirements)->first();
                                                $uploadedDocs = $this->getUploadedDocumentsForRequirement($documentId);
                                            @endphp

                                            @if($document && $requirement)
                                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                                                    <!-- Document Header -->
                                                    <div class="flex items-start justify-between mb-4">
                                                        <div class="flex-1">
                                                            <div class="flex items-center mb-2">
                                                                <i class="fas fa-file-alt text-indigo-500 mr-2"></i>
                                                                <h5 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                                                    {{ $document->title_ru }}
                                                                </h5>
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
                                                            @if($requirement['description_ru'])
                                                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-6">
                                                                    {{ $requirement['description_ru'] }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Uploaded Documents -->
                                                    @if($uploadedDocs->count() > 0)
                                                        <div class="mt-4 space-y-3">
                                                            <h6 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                                                Загруженные документы:
                                                            </h6>
                                                            @foreach($uploadedDocs as $doc)
                                                                <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                                                                    <div class="flex items-center flex-1">
                                                                        <i class="fas fa-file-pdf text-red-500 text-xl mr-3"></i>
                                                                        <div>
                                                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                                {{ $doc->title ?? 'Документ' }}
                                                                            </p>
                                                                            @if($doc->info)
                                                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                                    {{ $doc->info }}
                                                                                </p>
                                                                            @endif
                                                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                                                Загружено: {{ \Carbon\Carbon::parse($doc->created_at)->format('d.m.Y H:i') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex items-center space-x-2">
                                                                        <!-- Download Button -->
                                                                        <a
                                                                            href="{{ Storage::url($doc->file_url) }}"
                                                                            download
                                                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
                                                                            title="Скачать">
                                                                            <i class="fas fa-download text-lg"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="mt-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                                                            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                                                <i class="fas fa-info-circle mr-2"></i>
                                                                Документы не загружены
                                                            </p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Comments from Reviews -->
                                @if($criterion->first_comment || $criterion->industry_comment || $criterion->final_comment || $criterion->last_comment)
                                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-y-3">
                                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            <i class="fas fa-comments mr-2"></i>Комментарии от проверок
                                        </h4>

                                        @if($criterion->first_comment)
                                            <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-3 rounded">
                                                <div class="flex items-start">
                                                    <i class="fas fa-search text-blue-600 dark:text-blue-400 mt-1 mr-2"></i>
                                                    <div class="flex-1">
                                                        <p class="text-xs font-semibold text-blue-800 dark:text-blue-300 mb-1">Первичная проверка:</p>
                                                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $criterion->first_comment }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($criterion->industry_comment)
                                            <div class="bg-orange-50 dark:bg-orange-900/20 border-l-4 border-orange-500 p-3 rounded">
                                                <div class="flex items-start">
                                                    <i class="fas fa-industry text-orange-600 dark:text-orange-400 mt-1 mr-2"></i>
                                                    <div class="flex-1">
                                                        <p class="text-xs font-semibold text-orange-800 dark:text-orange-300 mb-1">Отраслевая проверка:</p>
                                                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $criterion->industry_comment }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($criterion->final_comment)
                                            <div class="bg-purple-50 dark:bg-purple-900/20 border-l-4 border-purple-500 p-3 rounded">
                                                <div class="flex items-start">
                                                    <i class="fas fa-clipboard-check text-purple-600 dark:text-purple-400 mt-1 mr-2"></i>
                                                    <div class="flex-1">
                                                        <p class="text-xs font-semibold text-purple-800 dark:text-purple-300 mb-1">Контрольная проверка:</p>
                                                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $criterion->final_comment }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($criterion->last_comment)
                                            <div class="bg-indigo-50 dark:bg-indigo-900/20 border-l-4 border-indigo-500 p-3 rounded">
                                                <div class="flex items-start">
                                                    <i class="fas fa-gavel text-indigo-600 dark:text-indigo-400 mt-1 mr-2"></i>
                                                    <div class="flex-1">
                                                        <p class="text-xs font-semibold text-indigo-800 dark:text-indigo-300 mb-1">Финальное решение:</p>
                                                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $criterion->last_comment }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        @else
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                    <i class="fas fa-inbox text-5xl mb-4 text-gray-400 dark:text-gray-600"></i>
                    <p class="text-lg font-medium">Нет критериев для отображения</p>
                </div>
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                <i class="fas fa-exclamation-triangle text-5xl mb-4 text-red-400"></i>
                <p class="text-lg font-medium">Заявка не найдена</p>
            </div>
        </div>
    @endif
</div>
