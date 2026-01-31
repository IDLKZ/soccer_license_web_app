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
                        Просмотр информации о заявке (режим администратора)
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="{{ $this->getApplicationStatusColor($application->application_status_category?->value ?? 'draft') }} px-4 py-2 rounded-full text-sm font-medium inline-flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ $application->application_status_category?->title_ru ?? 'Не определен' }}
                    </span>
                    @if($canManage)
                    <button
                        wire:click="openApplicationEditModal"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Изменить статус
                    </button>
                    @endif
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
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user?->name ?? '-' }}</p>
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

        <!-- Final Decision Progress -->
        @php
            $finalStats = $this->getFinalDecisionStats();
            $showFinalProgress = $finalStats['awaiting'] > 0 || $finalStats['decided'] > 0;
        @endphp

        @if($showFinalProgress)
        <div class="mb-6 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900 dark:to-purple-900 rounded-xl p-6 border border-indigo-200 dark:border-indigo-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                        <i class="fas fa-chart-pie mr-2 text-indigo-600 dark:text-indigo-400"></i>
                        Прогресс по критериям
                    </h3>
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="text-gray-600 dark:text-gray-400">
                            Всего: <span class="font-bold text-gray-900 dark:text-gray-100">{{ $finalStats['total'] }}</span>
                        </span>
                        @if($finalStats['awaiting'] > 0)
                        <span class="text-cyan-600 dark:text-cyan-400">
                            Ожидают решения: <span class="font-bold">{{ $finalStats['awaiting'] }}</span>
                        </span>
                        @endif
                        @if($finalStats['decided'] > 0)
                        <span class="text-green-600 dark:text-green-400">
                            Решено: <span class="font-bold">{{ $finalStats['decided'] }}</span>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Tabs Section -->
        @if(!empty($criteriaTabs))
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
            <!-- Tab Headers -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex -mb-px overflow-x-auto scrollbar-thin scrollbar-thumb-indigo-600 dark:scrollbar-thumb-indigo-700 scrollbar-track-indigo-100 dark:scrollbar-track-indigo-900 hover:scrollbar-thumb-indigo-500 dark:hover:scrollbar-thumb-indigo-600">
                    @foreach($criteriaTabs as $tab)
                        <button
                            wire:click="setActiveTab('{{ $tab['category']->id }}')"
                            class="{{ $activeTab === $tab['category']->id ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}
                             flex flex-col items-start px-6 py-4 border-b-2 font-medium text-sm transition-colors whitespace-nowrap"
                        >
                            <div class="flex items-center">
                                <i class="fas fa-folder mr-2"></i>
                                {{ $tab['title'] }}
                                <span class="ml-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded-full text-xs">
                                    {{ count($tab['criteria']) }}
                                </span>
                            </div>
                            @if($tab['status'])
                                <span class="mt-2 {{ $this->getCriterionStatusColorByValue($tab['status']->value) }} px-2 py-1 rounded-md text-xs font-medium">
                                    {{ $tab['status']->title_ru }}
                                </span>
                            @endif
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
                        <!-- Criterion Status -->
                        <div class="mb-6 bg-gray-50 dark:bg-gray-900 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                            <div class="mb-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                        Статус критерия: {{ $activeCategory['title'] }}
                                    </h3>
                                    @if($canManage)
                                    <button
                                        wire:click="openCriterionEditModal({{ $criterion->id }})"
                                        class="bg-amber-600 hover:bg-amber-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium inline-flex items-center transition-colors">
                                        <i class="fas fa-edit mr-1"></i>
                                        Редактировать критерий
                                    </button>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="{{ $this->getCriterionStatusColor($criterion) }} px-3 py-1 rounded-full text-xs font-medium">
                                        @if($criterion->is_first_passed === false || $criterion->is_industry_passed === false || $criterion->is_final_passed === false)
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

                            <!-- Progress indicators -->
                            <div class="grid grid-cols-3 gap-4 mt-4">
                                <div class="flex items-center space-x-2">
                                    @if($criterion->is_first_passed === true)
                                        <i class="fas fa-check-circle text-green-500"></i>
                                    @elseif($criterion->is_first_passed === false)
                                        <i class="fas fa-times-circle text-red-500"></i>
                                    @else
                                        <i class="fas fa-clock text-gray-400"></i>
                                    @endif
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Первичная проверка</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($criterion->is_industry_passed === true)
                                        <i class="fas fa-check-circle text-green-500"></i>
                                    @elseif($criterion->is_industry_passed === false)
                                        <i class="fas fa-times-circle text-red-500"></i>
                                    @else
                                        <i class="fas fa-clock text-gray-400"></i>
                                    @endif
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Отраслевая проверка</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($criterion->is_final_passed === true)
                                        <i class="fas fa-check-circle text-green-500"></i>
                                    @elseif($criterion->is_final_passed === false)
                                        <i class="fas fa-times-circle text-red-500"></i>
                                    @else
                                        <i class="fas fa-clock text-gray-400"></i>
                                    @endif
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Контрольная проверка</span>
                                </div>
                            </div>

                            <!-- Deadlines Section -->
                            @if($criterion->application_criteria_deadlines && $criterion->application_criteria_deadlines->count() > 0)
                                <div class="mt-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                    <h4 class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-3 flex items-center">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        Установленные дедлайны для доработки
                                    </h4>
                                    <div class="space-y-3">
                                        @foreach($criterion->application_criteria_deadlines->sortByDesc('created_at') as $deadline)
                                            <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-yellow-300 dark:border-yellow-700">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2 mb-2">
                                                            <span class="{{ $this->getCriterionStatusColorByValue($deadline->application_status?->value ?? 'draft') }} px-2 py-1 rounded-md text-xs font-medium">
                                                                {{ $deadline->application_status?->title_ru ?? '-' }}
                                                            </span>
                                                            @php
                                                                $now = now();
                                                                $isExpired = $deadline->deadline_end_at?->lt($now) ?? false;
                                                                $isUpcoming = ($deadline->deadline_end_at?->diffInDays($now) ?? 999) <= 3 && !$isExpired;
                                                            @endphp
                                                            @if($isExpired)
                                                                <span class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-1 rounded-md text-xs font-medium">
                                                                    <i class="fas fa-exclamation-circle mr-1"></i> Просрочен
                                                                </span>
                                                            @elseif($isUpcoming)
                                                                <span class="bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200 px-2 py-1 rounded-md text-xs font-medium">
                                                                    <i class="fas fa-clock mr-1"></i> Скоро истекает
                                                                </span>
                                                            @endif
                                                        </div>

                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                                            @if($deadline->deadline_start_at)
                                                                <div class="text-gray-700 dark:text-gray-300">
                                                                    <i class="fas fa-hourglass-start mr-1 text-blue-500"></i>
                                                                    <span class="font-medium">Начало:</span>
                                                                    {{ $deadline->deadline_start_at?->format('d.m.Y H:i') ?? '-' }}
                                                                </div>
                                                            @endif
                                                            <div class="text-gray-700 dark:text-gray-300">
                                                                <i class="fas fa-hourglass-end mr-1 {{ $isExpired ? 'text-red-500' : 'text-yellow-500' }}"></i>
                                                                <span class="font-medium">Крайний срок:</span>
                                                                {{ $deadline->deadline_end_at?->format('d.m.Y H:i') ?? '-' }}
                                                            </div>
                                                        </div>

                                                        @if($isExpired)
                                                            <div class="mt-2 text-xs text-red-600 dark:text-red-400">
                                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                                Просрочен на {{ $deadline->deadline_end_at?->diffForHumans($now, true) ?? '-' }}
                                                            </div>
                                                        @else
                                                            <div class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                                                                <i class="fas fa-info-circle mr-1"></i>
                                                                Осталось {{ $deadline->deadline_end_at?->diffForHumans($now, true) ?? '-' }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Comments from Previous Reviews -->
                            @if($criterion->first_comment || $criterion->industry_comment || $criterion->final_comment || $criterion->last_comment)
                                <div class="mt-4 space-y-3">
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

                        <!-- Documents by Requirements -->
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
                                        <!-- Document Header -->
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
                                                    <div class="text-sm ml-6 prose-content text-gray-600 dark:text-gray-400">
                                                        {!! $document->description_ru !!}
                                                    </div>
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

                                        <!-- Uploaded Documents List -->
                                        <div class="mt-4 space-y-3">
                                            @if($uploadedDocs->count() > 0)
                                                @foreach($uploadedDocs as $doc)
                                                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-600 transition-colors cursor-pointer" wire:click="openDocumentEditModal({{ $doc->id }})">
                                                        <div class="flex items-start justify-between">
                                                            <div class="flex items-start flex-1">
                                                                <i class="fas fa-file text-gray-400 mr-3 mt-1"></i>
                                                                <div class="flex-1">
                                                                    <div class="flex items-center flex-wrap gap-2 mb-2">
                                                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                            {{ $doc->title ?? 'Документ' }}
                                                                        </span>

                                                                        <!-- Status badges -->
                                                                        @if($doc->is_first_passed === false)
                                                                            <span class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-1 rounded text-xs font-medium">
                                                                                <i class="fas fa-times mr-1"></i>Не прошел первичную
                                                                            </span>
                                                                        @elseif($doc->is_industry_passed === false)
                                                                            <span class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-1 rounded text-xs font-medium">
                                                                                <i class="fas fa-times mr-1"></i>Не прошел отраслевую
                                                                            </span>
                                                                        @elseif($doc->is_final_passed === false)
                                                                            <span class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-1 rounded text-xs font-medium">
                                                                                <i class="fas fa-times mr-1"></i>Не прошел контрольную
                                                                            </span>
                                                                        @elseif($doc->is_first_passed === null && $doc->is_industry_passed === null && $doc->is_final_passed === null)
                                                                            <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-2 py-1 rounded text-xs font-medium">
                                                                                <i class="fas fa-clock mr-1"></i>Ожидает проверки
                                                                            </span>
                                                                        @elseif($doc->is_first_passed === true && $doc->is_industry_passed === true && $doc->is_final_passed === true)
                                                                            <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-2 py-1 rounded text-xs font-medium">
                                                                                <i class="fas fa-check mr-1"></i>Все этапы пройдены
                                                                            </span>
                                                                        @elseif($doc->is_first_passed === true && $doc->is_industry_passed === null)
                                                                            <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-2 py-1 rounded text-xs font-medium">
                                                                                <i class="fas fa-check mr-1"></i>Прошел первичную
                                                                            </span>
                                                                        @elseif($doc->is_first_passed === true && $doc->is_industry_passed === true && $doc->is_final_passed === null)
                                                                            <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-2 py-1 rounded text-xs font-medium">
                                                                                <i class="fas fa-check mr-1"></i>Прошел первичную и отраслевую
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    @if($doc->info)
                                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ $doc->info }}</p>
                                                                    @endif

                                                                    <!-- Show existing comments -->
                                                                    @if($doc->first_comment)
                                                                        <div class="mt-2 p-2 bg-yellow-50 dark:bg-yellow-900 rounded text-xs">
                                                                            <span class="font-medium">Комментарий первичной проверки:</span> {{ $doc->first_comment }}
                                                                        </div>
                                                                    @endif
                                                                    @if($doc->industry_comment)
                                                                        <div class="mt-2 p-2 bg-orange-50 dark:bg-orange-900 rounded text-xs">
                                                                            <span class="font-medium">Комментарий отраслевой проверки:</span> {{ $doc->industry_comment }}
                                                                        </div>
                                                                    @endif
                                                                    @if($doc->control_comment)
                                                                        <div class="mt-2 p-2 bg-purple-50 dark:bg-purple-900 rounded text-xs">
                                                                            <span class="font-medium">Комментарий контрольной проверки:</span> {{ $doc->control_comment }}
                                                                        </div>
                                                                    @endif

                                                                    <div class="flex items-center space-x-3 mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                                        <span>
                                                                            <i class="fas fa-user mr-1"></i>{{ $doc->uploaded_by ?? 'Неизвестно' }}
                                                                        </span>
                                                                        <span>
                                                                            <i class="fas fa-clock mr-1"></i>{{ $doc->created_at?->format('d.m.Y H:i') ?? '-' }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Actions -->
                                                            <div class="flex items-center space-x-2 ml-4" onclick="event.stopPropagation()">
                                                                @if($canManage)
                                                                    <button wire:click="openDocumentEditModal({{ $doc->id }})"
                                                                       class="text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300 p-2"
                                                                       title="Редактировать">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                @endif
                                                                @if($doc->file_url)
                                                                    <a href="{{ Storage::url($doc->file_url) }}" target="_blank"
                                                                       class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 p-2"
                                                                       title="Просмотр/Скачать">
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
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

                        <!-- Initial Reports Section -->
                        @if(isset($initialReportsByCriteria[$criterion->id]) && count($initialReportsByCriteria[$criterion->id]) > 0)
                            <div class="mt-6 bg-blue-50 dark:bg-blue-900 rounded-lg p-6 border border-blue-200 dark:border-blue-700">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        <i class="fas fa-clipboard-list text-blue-600 mr-2"></i>
                                        Первичные отчеты по критерию
                                    </h4>
                                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ count($initialReportsByCriteria[$criterion->id]) }}
                                    </span>
                                </div>

                                <div class="space-y-4">
                                    @foreach($initialReportsByCriteria[$criterion->id] as $report)
                                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center mb-2">
                                                        <i class="fas fa-file-alt text-blue-600 mr-2"></i>
                                                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">
                                                            Первичный отчет #{{ $report->id }}
                                                        </h4>
                                                        <span class="ml-3 text-xs text-gray-500 dark:text-gray-400">
                                                            от {{ $report->created_at->format('d.m.Y H:i') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Reports Section -->
                        @if(isset($reportsByCriteria[$criterion->id]))
                            <div class="mt-8 bg-gray-50 dark:bg-gray-900 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        <i class="fas fa-clipboard-list text-indigo-500 mr-2"></i>
                                        Отчеты по критерию
                                    </h3>
                                    <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ count($reportsByCriteria[$criterion->id]) }}
                                    </span>
                                </div>

                                @if(count($reportsByCriteria[$criterion->id]) > 0)
                                    <div class="space-y-4">
                                        @foreach($reportsByCriteria[$criterion->id] as $report)
                                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center mb-2">
                                                            <i class="fas fa-file-alt text-indigo-500 mr-2"></i>
                                                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">
                                                                Отчет #{{ $report->id }}
                                                            </h4>
                                                            <span class="ml-3 text-xs text-gray-500 dark:text-gray-400">
                                                                от {{ $report->created_at->format('d.m.Y H:i') }}
                                                            </span>
                                                        </div>

                                                        @if($report->application_criterion)
                                                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                                Критерий: {{ $report->application_criterion->category_document->title_ru ?? 'Не указан' }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-clipboard text-3xl mb-2"></i>
                                        <p>Пока нет отчетов по этому критерию</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </div>

        <!-- Department Reports Section -->
        @if(!empty($departmentReports) && count($departmentReports) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 mt-6">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-building text-indigo-500 mr-2"></i>
                        Общие отчеты департамента
                    </h3>
                    <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 px-3 py-1 rounded-full text-sm font-medium">
                        {{ count($departmentReports) }}
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach($departmentReports as $report)
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-file-alt text-indigo-500 mr-2"></i>
                                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">
                                            Отчет #{{ $report->id }}
                                        </h4>
                                        <span class="ml-3 text-xs text-gray-500 dark:text-gray-400">
                                            от {{ $report->created_at->format('d.m.Y H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Solutions Section -->
        @if(!empty($solutions) && count($solutions) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 mt-6">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-gavel text-purple-500 mr-2"></i>
                        Решения комиссии
                    </h3>
                    <span class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 px-3 py-1 rounded-full text-sm font-medium">
                        {{ count($solutions) }}
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach($solutions as $solution)
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-file-contract text-purple-500 mr-2"></i>
                                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">
                                            Решение #{{ $solution->id }}
                                        </h4>
                                        <span class="ml-3 text-xs text-gray-500 dark:text-gray-400">
                                            от {{ $solution->created_at->format('d.m.Y H:i') }}
                                        </span>
                                    </div>
                                    @if($solution->user)
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Создал: {{ $solution->user->name }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- License Certificates Section -->
        @if(!empty($licenseCertificates) && count($licenseCertificates) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 mt-6">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-certificate text-green-500 mr-2"></i>
                        Сертификаты лицензии
                    </h3>
                    <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-3 py-1 rounded-full text-sm font-medium">
                        {{ count($licenseCertificates) }}
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach($licenseCertificates as $certificate)
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-award text-green-500 mr-2"></i>
                                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">
                                            Сертификат #{{ $certificate->id }}
                                        </h4>
                                        <span class="ml-3 text-xs text-gray-500 dark:text-gray-400">
                                            от {{ $certificate->created_at->format('d.m.Y H:i') }}
                                        </span>
                                    </div>
                                    @if($certificate->type_ru)
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $certificate->type_ru }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @else
            <div class="text-center py-12">
                <div class="bg-gray-100 dark:bg-gray-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                    Нет критериев
                </h3>
                <p class="text-gray-500 dark:text-gray-400">
                    Для этой заявки не определены критерии.
                </p>
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

    <!-- Document Edit Modal -->
    @if($showDocumentEditModal && $editingDocument)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity" wire:click="closeDocumentEditModal"></div>

            <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <form wire:submit="saveDocument">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                <i class="fas fa-edit text-indigo-500 mr-2"></i>
                                Редактирование документа
                            </h3>
                            <button type="button" wire:click="closeDocumentEditModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-2">
                            <!-- Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Название документа</label>
                                <input type="text" wire:model="editDocTitle"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100">
                            </div>

                            <!-- Info -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание</label>
                                <textarea wire:model="editDocInfo" rows="2"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100"></textarea>
                            </div>

                            <!-- Statuses Grid -->
                            <div class="mb-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Выберите "Ожидает (null)" для сброса статуса проверки
                                </p>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Первичная проверка</label>
                                    <select wire:model="editDocIsFirstPassed"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100">
                                        <option value="">Ожидает (null)</option>
                                        <option value="1">Пройдена</option>
                                        <option value="0">Не пройдена</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Отраслевая проверка</label>
                                    <select wire:model="editDocIsIndustryPassed"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100">
                                        <option value="">Ожидает (null)</option>
                                        <option value="1">Пройдена</option>
                                        <option value="0">Не пройдена</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Контрольная проверка</label>
                                    <select wire:model="editDocIsFinalPassed"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100">
                                        <option value="">Ожидает (null)</option>
                                        <option value="1">Пройдена</option>
                                        <option value="0">Не пройдена</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Comments -->
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                                <label class="block text-sm font-medium text-yellow-800 dark:text-yellow-200 mb-1">
                                    <i class="fas fa-search mr-1"></i> Комментарий первичной проверки
                                </label>
                                <textarea wire:model="editDocFirstComment" rows="2" placeholder="Оставьте пустым для сброса в null"
                                    class="w-full border border-yellow-300 dark:border-yellow-700 rounded-lg px-3 py-2 text-sm focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:text-gray-100"></textarea>
                            </div>

                            <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg">
                                <label class="block text-sm font-medium text-orange-800 dark:text-orange-200 mb-1">
                                    <i class="fas fa-industry mr-1"></i> Комментарий отраслевой проверки
                                </label>
                                <textarea wire:model="editDocIndustryComment" rows="2" placeholder="Оставьте пустым для сброса в null"
                                    class="w-full border border-orange-300 dark:border-orange-700 rounded-lg px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-gray-100"></textarea>
                            </div>

                            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                                <label class="block text-sm font-medium text-purple-800 dark:text-purple-200 mb-1">
                                    <i class="fas fa-clipboard-check mr-1"></i> Комментарий контрольной проверки
                                </label>
                                <textarea wire:model="editDocControlComment" rows="2" placeholder="Оставьте пустым для сброса в null"
                                    class="w-full border border-purple-300 dark:border-purple-700 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-gray-100"></textarea>
                            </div>

                            <!-- Document info -->
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <span>Загрузил: {{ $editingDocument->uploaded_by ?? 'Неизвестно' }}</span>
                                <span>{{ $editingDocument->created_at?->format('d.m.Y H:i') ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:w-auto sm:text-sm">
                            <i class="fas fa-save mr-2"></i>
                            Сохранить
                        </button>
                        @if($editingDocument->file_url)
                        <a href="{{ Storage::url($editingDocument->file_url) }}" target="_blank"
                           class="w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 sm:w-auto sm:text-sm">
                            <i class="fas fa-download mr-2"></i>
                            Скачать файл
                        </a>
                        @endif
                        <button type="button" wire:click="closeDocumentEditModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto sm:text-sm">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Criterion Edit Modal -->
    @if($showCriterionEditModal && $editingCriterion)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity" wire:click="closeCriterionEditModal"></div>

            <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <form wire:submit="saveCriterion">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                <i class="fas fa-edit text-amber-500 mr-2"></i>
                                Редактирование критерия
                            </h3>
                            <button type="button" wire:click="closeCriterionEditModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-2">
                            <!-- Criterion Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Статус критерия</label>
                                <select wire:model="editCriterionStatusId"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100">
                                    <option value="">-- Не выбран --</option>
                                    @foreach($applicationStatuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->title_ru }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Passed Flags -->
                            <div class="mb-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Выберите "Ожидает (null)" для сброса статуса проверки. Оставьте комментарии пустыми для сброса.
                                </p>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Первичная проверка</label>
                                    <select wire:model="editCriterionIsFirstPassed"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100">
                                        <option value="">Ожидает (null)</option>
                                        <option value="1">Пройдена</option>
                                        <option value="0">Не пройдена</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Отраслевая проверка</label>
                                    <select wire:model="editCriterionIsIndustryPassed"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100">
                                        <option value="">Ожидает (null)</option>
                                        <option value="1">Пройдена</option>
                                        <option value="0">Не пройдена</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Контрольная проверка</label>
                                    <select wire:model="editCriterionIsFinalPassed"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100">
                                        <option value="">Ожидает (null)</option>
                                        <option value="1">Пройдена</option>
                                        <option value="0">Не пройдена</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Comments -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                                <label class="block text-sm font-medium text-blue-800 dark:text-blue-200 mb-1">
                                    <i class="fas fa-search mr-1"></i> Комментарий первичной проверки
                                </label>
                                <textarea wire:model="editCriterionFirstComment" rows="2" placeholder="Оставьте пустым для сброса в null"
                                    class="w-full border border-blue-300 dark:border-blue-700 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100"></textarea>
                            </div>

                            <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg">
                                <label class="block text-sm font-medium text-orange-800 dark:text-orange-200 mb-1">
                                    <i class="fas fa-industry mr-1"></i> Комментарий отраслевой проверки
                                </label>
                                <textarea wire:model="editCriterionIndustryComment" rows="2" placeholder="Оставьте пустым для сброса в null"
                                    class="w-full border border-orange-300 dark:border-orange-700 rounded-lg px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-gray-100"></textarea>
                            </div>

                            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                                <label class="block text-sm font-medium text-purple-800 dark:text-purple-200 mb-1">
                                    <i class="fas fa-clipboard-check mr-1"></i> Комментарий контрольной проверки
                                </label>
                                <textarea wire:model="editCriterionFinalComment" rows="2" placeholder="Оставьте пустым для сброса в null"
                                    class="w-full border border-purple-300 dark:border-purple-700 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-gray-100"></textarea>
                            </div>

                            <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg">
                                <label class="block text-sm font-medium text-indigo-800 dark:text-indigo-200 mb-1">
                                    <i class="fas fa-gavel mr-1"></i> Финальное решение
                                </label>
                                <textarea wire:model="editCriterionLastComment" rows="2" placeholder="Оставьте пустым для сброса в null"
                                    class="w-full border border-indigo-300 dark:border-indigo-700 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-amber-600 text-base font-medium text-white hover:bg-amber-700 sm:w-auto sm:text-sm">
                            <i class="fas fa-save mr-2"></i>
                            Сохранить
                        </button>
                        <button type="button" wire:click="closeCriterionEditModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto sm:text-sm">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Application Status Edit Modal -->
    @if($showApplicationEditModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity" wire:click="closeApplicationEditModal"></div>

            <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="saveApplication">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                <i class="fas fa-edit text-indigo-500 mr-2"></i>
                                Изменение статуса заявки
                            </h3>
                            <button type="button" wire:click="closeApplicationEditModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Категория статуса заявки</label>
                                <select wire:model="editApplicationCategoryId"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-100">
                                    @foreach($applicationStatusCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->title_ru }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Изменение статуса заявки влияет на общее состояние заявки для клуба.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:w-auto sm:text-sm">
                            <i class="fas fa-save mr-2"></i>
                            Сохранить
                        </button>
                        <button type="button" wire:click="closeApplicationEditModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto sm:text-sm">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
