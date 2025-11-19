<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    Заявки на лицензирование
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Просмотр и проверка заявок от всех клубов
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Search -->
                <div class="relative">
                    <input
                        type="text"
                        wire:model.live.debounce.500ms="search"
                        placeholder="Поиск заявок..."
                        class="w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                    >
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Pending -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ожидают загрузки</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                    <i class="fas fa-upload text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>

        <!-- In Review -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">На проверке</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ $stats['in_review'] }}</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Завершено</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $stats['completed'] }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>

        <!-- Cancelled -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Отменено</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $stats['cancelled'] }}</p>
                </div>
                <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                    <i class="fas fa-times-circle text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
        <!-- Tab Headers -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex -mb-px">
                <button
                    wire:click="setActiveTab('pending')"
                    class="{{ $activeTab === 'pending' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}
                     flex items-center px-6 py-4 border-b-2 font-medium text-sm transition-colors"
                >
                    <i class="fas fa-upload mr-2"></i>
                    Ожидают загрузки
                    @if($stats['pending'] > 0)
                    <span class="ml-2 bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300 px-2 py-1 rounded-full text-xs font-medium">
                        {{ $stats['pending'] }}
                    </span>
                    @endif
                </button>

                <button
                    wire:click="setActiveTab('in_review')"
                    class="{{ $activeTab === 'in_review' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}
                     flex items-center px-6 py-4 border-b-2 font-medium text-sm transition-colors"
                >
                    <i class="fas fa-clock mr-2"></i>
                    На проверке
                    @if($stats['in_review'] > 0)
                    <span class="ml-2 bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300 px-2 py-1 rounded-full text-xs font-medium">
                        {{ $stats['in_review'] }}
                    </span>
                    @endif
                </button>

                <button
                    wire:click="setActiveTab('completed')"
                    class="{{ $activeTab === 'completed' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}
                     flex items-center px-6 py-4 border-b-2 font-medium text-sm transition-colors"
                >
                    <i class="fas fa-check-circle mr-2"></i>
                    Завершенные
                    @if($stats['completed'] > 0)
                    <span class="ml-2 bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300 px-2 py-1 rounded-full text-xs font-medium">
                        {{ $stats['completed'] }}
                    </span>
                    @endif
                </button>

                <button
                    wire:click="setActiveTab('cancelled')"
                    class="{{ $activeTab === 'cancelled' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}
                     flex items-center px-6 py-4 border-b-2 font-medium text-sm transition-colors"
                >
                    <i class="fas fa-times-circle mr-2"></i>
                    Отмененные
                    @if($stats['cancelled'] > 0)
                    <span class="ml-2 bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300 px-2 py-1 rounded-full text-xs font-medium">
                        {{ $stats['cancelled'] }}
                    </span>
                    @endif
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            @if($applications->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($applications as $application)
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300 hover:scale-[1.02]">
                            <!-- Application Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mr-3">
                                            {{ $application->licence?->title_ru ?? 'Без названия' }}
                                        </h3>
                                        <span class="{{ $this->getApplicationStatusColor($application->application_status_category?->value ?? 'draft') }} px-3 py-1 rounded-full text-xs font-medium inline-flex items-center">
                                            <i class="{{ $this->getApplicationStatusIcon($application->application_status_category?->value ?? 'draft') }} mr-1"></i>
                                            {{ $application->application_status_category?->title_ru ?? 'Не определен' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <i class="fas fa-building mr-2"></i>
                                        {{ $application->club?->short_name_ru ?? $application->club?->full_name_ru ?? 'Неизвестный клуб' }}
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($application->deadline)
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Дедлайн</p>
                                        <p class="text-sm font-medium {{ $application->deadline?->isPast() ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-100' }}">
                                            {{ $application->deadline?->format('d.m.Y') ?? '-' }}
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- License Info -->
                            @if($application->licence)
                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 mb-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-certificate text-purple-500 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Информация о лицензии</span>
                                </div>
                                <div class="grid grid-cols-1 gap-2 text-sm">
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Сезон:</span>
                                        <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $application->licence?->season?->title_ru ?? '-' }}</span>
                                    </div>
                                    @if($application->licence?->league)
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Соревнование:</span>
                                        <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $application->licence?->league?->title_ru ?? '-' }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Criteria Status -->
                            @if($application->application_criteria?->count() > 0)
                            <div class="mb-4">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-tasks text-blue-500 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Критерии ({{ $application->application_criteria?->count() ?? 0 }})</span>
                                </div>

                                <div class="space-y-2">
                                    @foreach($application->application_criteria as $criterion)
                                        <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700">
                                            <div class="flex flex-col flex-1 mr-2">
                                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $criterion->category_document?->title_ru ?? 'Критерий' }}
                                                </span>
                                                @if($criterion->application_status)
                                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        {{ $criterion->application_status?->title_ru ?? '-' }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                @if($criterion->application_status)
                                                    <span class="{{ $this->getStatusBadgeColor($criterion->application_status?->value ?? 'draft') }} px-2 py-1 rounded text-xs font-medium whitespace-nowrap">
                                                        @if(str_contains($criterion->application_status?->value ?? '', 'revision'))
                                                            <i class="fas fa-undo mr-1"></i>
                                                        @elseif(str_contains($criterion->application_status?->value ?? '', 'awaiting'))
                                                            <i class="fas fa-clock mr-1"></i>
                                                        @elseif(str_contains($criterion->application_status?->value ?? '', 'approved'))
                                                            <i class="fas fa-check mr-1"></i>
                                                        @elseif(($criterion->application_status?->value ?? '') === 'rejected' || ($criterion->application_status?->value ?? '') === 'revoked')
                                                            <i class="fas fa-times mr-1"></i>
                                                        @endif
                                                        {{ $criterion->application_status?->title_ru ?? '-' }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Criteria Summary -->
                                @php
                                    $criteriaStats = $this->getCriteriaByStatus($application);
                                @endphp
                                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($criteriaStats['by_status'] as $statusValue => $data)
                                            @if($data['status'])
                                                <span class="{{ $this->getStatusBadgeColor($statusValue) }} px-2 py-1 rounded text-xs">
                                                    {{ $data['status']?->title_ru ?? '-' }}: {{ $data['count'] ?? 0 }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Создана: {{ $application->created_at?->format('d.m.Y H:i') ?? '-' }}
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('department.application.detail', $application->id) }}" class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                                        <i class="fas fa-eye mr-2"></i>
                                        Подробнее
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="bg-gray-100 dark:bg-gray-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        @if($search)
                            Заявки не найдены
                        @else
                            Нет заявок в этой категории
                        @endif
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">
                        @if($search)
                            Попробуйте изменить параметры поиска
                        @else
                            @if($activeTab === 'pending')
                                Нет заявок ожидающих загрузки документов
                            @elseif($activeTab === 'in_review')
                                Нет заявок на проверке
                            @elseif($activeTab === 'completed')
                                Нет завершенных заявок
                            @else
                                Нет отмененных заявок
                            @endif
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
