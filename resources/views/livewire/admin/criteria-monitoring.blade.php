<div>
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    <i class="fas fa-heartbeat text-red-500 mr-3"></i>
                    Мониторинг критериев
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Отслеживание и исправление рассинхронизации между документами и критериями
                </p>
            </div>
            @if($canSync && $stats['total'] > 0)
                <button wire:click="syncAll"
                        wire:loading.attr="disabled"
                        wire:target="syncAll"
                        class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700
                               text-white px-6 py-3 rounded-xl font-medium shadow-lg
                               hover:shadow-xl transition-all duration-300 flex items-center space-x-2
                               disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="syncAll">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Исправить все ({{ $stats['total'] }})
                    </span>
                    <span wire:loading wire:target="syncAll">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Синхронизация...
                    </span>
                </button>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Errors -->
        <button wire:click="setActiveTab('all')"
                class="{{ $activeTab === 'all' ? 'ring-2 ring-red-500 bg-red-50 dark:bg-red-900/20' : 'bg-white dark:bg-gray-800' }}
                       rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6
                       hover:shadow-xl transition-all duration-300 text-left">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-600 dark:text-red-400">
                        Всего ошибок
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                        {{ $stats['total'] }}
                    </p>
                </div>
                <div class="bg-red-100 dark:bg-red-900/50 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </button>

        <!-- First Level -->
        <button wire:click="setActiveTab('first')"
                class="{{ $activeTab === 'first' ? 'ring-2 ring-yellow-500 bg-yellow-50 dark:bg-yellow-900/20' : 'bg-white dark:bg-gray-800' }}
                       rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6
                       hover:shadow-xl transition-all duration-300 text-left">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">
                        Первичная проверка
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                        {{ $stats['first'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Документы пройдены, статус = 1
                    </p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/50 p-3 rounded-full">
                    <i class="fas fa-file-alt text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
        </button>

        <!-- Second Level -->
        <button wire:click="setActiveTab('second')"
                class="{{ $activeTab === 'second' ? 'ring-2 ring-orange-500 bg-orange-50 dark:bg-orange-900/20' : 'bg-white dark:bg-gray-800' }}
                       rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6
                       hover:shadow-xl transition-all duration-300 text-left">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-600 dark:text-orange-400">
                        Отраслевая проверка
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                        {{ $stats['second'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Документы пройдены, статус = 1
                    </p>
                </div>
                <div class="bg-orange-100 dark:bg-orange-900/50 p-3 rounded-full">
                    <i class="fas fa-industry text-orange-600 dark:text-orange-400"></i>
                </div>
            </div>
        </button>

        <!-- Third Level -->
        <button wire:click="setActiveTab('third')"
                class="{{ $activeTab === 'third' ? 'ring-2 ring-purple-500 bg-purple-50 dark:bg-purple-900/20' : 'bg-white dark:bg-gray-800' }}
                       rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6
                       hover:shadow-xl transition-all duration-300 text-left">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-600 dark:text-purple-400">
                        Контрольная проверка
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                        {{ $stats['third'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Документы пройдены, статус = 1
                    </p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900/50 p-3 rounded-full">
                    <i class="fas fa-clipboard-check text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </button>
    </div>

    <!-- Search -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <input type="text"
                           wire:model.live.debounce.500ms="search"
                           placeholder="Поиск по ID, клубу, лицензии или категории..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                  focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                  placeholder-gray-500 dark:placeholder-gray-400">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Criteria Grid -->
    @if($criteria->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($criteria as $criterion)
                @php
                    $level = $this->getErrorLevel($criterion);
                    $levelColor = match($level) {
                        'first' => 'yellow',
                        'second' => 'orange',
                        'third' => 'purple',
                        default => 'gray',
                    };
                    $levelText = match($level) {
                        'first' => 'Первичная',
                        'second' => 'Отраслевая',
                        'third' => 'Контрольная',
                        default => 'Неизвестно',
                    };
                @endphp
                <div class="relative bg-gradient-to-br from-slate-50 to-red-50 dark:from-slate-800 dark:to-red-900/30
                            rounded-xl shadow-lg border border-red-200 dark:border-red-800/50
                            overflow-hidden">
                    <!-- Error Badge -->
                    <div class="absolute top-3 right-3 z-10">
                        <span class="bg-{{ $levelColor }}-100 text-{{ $levelColor }}-800 dark:bg-{{ $levelColor }}-900 dark:text-{{ $levelColor }}-200
                                     px-2 py-1 rounded-full text-xs font-medium">
                            {{ $levelText }}
                        </span>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <!-- Header -->
                        <div class="mb-4 pr-20">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1 line-clamp-2">
                                {{ $criterion->category_document?->title_ru ?? 'Критерий' }}
                            </h3>
                            @if(($criterion->category_document?->title_kk ?? '') != ($criterion->category_document?->title_ru ?? ''))
                                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1">
                                    {{ $criterion->category_document?->title_kk ?? '-' }}
                                </p>
                            @endif
                        </div>

                        <!-- IDs Info -->
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 mb-4">
                            <div class="grid grid-cols-3 gap-2 text-xs">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Критерий:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100 ml-1">#{{ $criterion->id }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Заявка:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100 ml-1">#{{ $criterion->application_id }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Категория:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100 ml-1">#{{ $criterion->category_id }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Status Error -->
                        <div class="bg-red-100 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-lg p-3 mb-4">
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                                <div class="text-xs">
                                    <p class="text-red-700 dark:text-red-300 font-medium">
                                        Текущий статус: {{ $criterion->application_status?->title_ru ?? 'ID: ' . $criterion->status_id }}
                                    </p>
                                    <p class="text-red-600 dark:text-red-400 mt-1">
                                        Ожидаемый: {{ $this->getExpectedStatus($level) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Application Info -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm">
                                <i class="fas fa-building text-gray-400 mr-2 w-4"></i>
                                <span class="text-gray-600 dark:text-gray-400 truncate">
                                    {{ $criterion->application?->club?->short_name_ru ?? '-' }}
                                </span>
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-certificate text-gray-400 mr-2 w-4"></i>
                                <span class="text-gray-600 dark:text-gray-400 truncate">
                                    {{ $criterion->application?->licence?->title_ru ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <!-- Check Status -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mb-4">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-500 dark:text-gray-400">Первичная:</span>
                                    @if($criterion->is_first_passed === true)
                                        <span class="text-green-600 dark:text-green-400 font-medium">
                                            <i class="fas fa-check mr-1"></i>Пройдена
                                        </span>
                                    @elseif($criterion->is_first_passed === false)
                                        <span class="text-red-600 dark:text-red-400 font-medium">
                                            <i class="fas fa-times mr-1"></i>Не пройдена
                                        </span>
                                    @else
                                        <span class="text-gray-400">Не проверялась</span>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-500 dark:text-gray-400">Отраслевая:</span>
                                    @if($criterion->is_industry_passed === true)
                                        <span class="text-green-600 dark:text-green-400 font-medium">
                                            <i class="fas fa-check mr-1"></i>Пройдена
                                        </span>
                                    @elseif($criterion->is_industry_passed === false)
                                        <span class="text-red-600 dark:text-red-400 font-medium">
                                            <i class="fas fa-times mr-1"></i>Не пройдена
                                        </span>
                                    @else
                                        <span class="text-gray-400">Не проверялась</span>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-500 dark:text-gray-400">Контрольная:</span>
                                    @if($criterion->is_final_passed === true)
                                        <span class="text-green-600 dark:text-green-400 font-medium">
                                            <i class="fas fa-check mr-1"></i>Пройдена
                                        </span>
                                    @elseif($criterion->is_final_passed === false)
                                        <span class="text-red-600 dark:text-red-400 font-medium">
                                            <i class="fas fa-times mr-1"></i>Не пройдена
                                        </span>
                                    @else
                                        <span class="text-gray-400">Не проверялась</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.application-detailed', $criterion->application_id) }}"
                               class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg
                                      text-sm font-medium text-center transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Открыть заявку
                            </a>
                            @if($canSync)
                                <button wire:click="syncCriterion({{ $criterion->id }}, '{{ $level }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="syncCriterion({{ $criterion->id }}, '{{ $level }}')"
                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg
                                               text-sm font-medium transition-colors disabled:opacity-50">
                                    <span wire:loading.remove wire:target="syncCriterion({{ $criterion->id }}, '{{ $level }}')">
                                        <i class="fas fa-wrench"></i>
                                    </span>
                                    <span wire:loading wire:target="syncCriterion({{ $criterion->id }}, '{{ $level }}')">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-12">
            <div class="text-center">
                <div class="bg-green-100 dark:bg-green-900/50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                    Ошибок не найдено
                </h3>
                <p class="text-gray-500 dark:text-gray-400">
                    @if($search)
                        По вашему запросу ничего не найдено.
                    @else
                        Все критерии синхронизированы с документами.
                    @endif
                </p>
            </div>
        </div>
    @endif
</div>
