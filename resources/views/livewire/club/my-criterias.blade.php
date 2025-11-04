<div>
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    Мои критерии
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Управление критериями для проверки лицензионных заявок
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <button wire:click="setActiveTab('active')"
                class="{{ $activeTab === 'active' ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'bg-white dark:bg-gray-800' }}
                       rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6
                       hover:shadow-xl transition-all duration-300 text-left">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">
                        Активные
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                        {{ $stats['active'] }}
                    </p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </button>

        <button wire:click="setActiveTab('in_review')"
                class="{{ $activeTab === 'in_review' ? 'ring-2 ring-yellow-500 bg-yellow-50 dark:bg-yellow-900/20' : 'bg-white dark:bg-gray-800' }}
                       rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6
                       hover:shadow-xl transition-all duration-300 text-left">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">
                        На проверке
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                        {{ $stats['in_review'] }}
                    </p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/50 p-3 rounded-full">
                    <i class="fas fa-search text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
        </button>

        <button wire:click="setActiveTab('approved')"
                class="{{ $activeTab === 'approved' ? 'ring-2 ring-green-500 bg-green-50 dark:bg-green-900/20' : 'bg-white dark:bg-gray-800' }}
                       rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6
                       hover:shadow-xl transition-all duration-300 text-left">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600 dark:text-green-400">
                        Одобрено
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                        {{ $stats['approved'] }}
                    </p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </button>

        <button wire:click="setActiveTab('rejected')"
                class="{{ $activeTab === 'rejected' ? 'ring-2 ring-red-500 bg-red-50 dark:bg-red-900/20' : 'bg-white dark:bg-gray-800' }}
                       rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6
                       hover:shadow-xl transition-all duration-300 text-left">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-600 dark:text-red-400">
                        Отказано
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                        {{ $stats['rejected'] }}
                    </p>
                </div>
                <div class="bg-red-100 dark:bg-red-900/50 p-3 rounded-full">
                    <i class="fas fa-times-circle text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </button>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <input type="text"
                           wire:model.live.debounce.500ms="search"
                           placeholder="Поиск по лицензии, клубу или категории..."
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

    <!-- Criterias Grid -->
    @if($criterias->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($criterias as $criteria)
                <a href="{{ route('club.application.detail', $criteria->application_id) }}"
                   class="block relative bg-gradient-to-br from-slate-50 to-blue-50 dark:from-slate-800 dark:to-blue-900/30
                          rounded-xl shadow-lg border border-slate-200 dark:border-blue-800/50
                          hover:shadow-xl hover:border-blue-300 dark:hover:border-blue-700 transition-all duration-300 overflow-hidden group">
                    <!-- Header -->
                    <div class="p-6 relative z-10">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1 line-clamp-2">
                                    {{ $criteria->category_document->title_ru }}
                                </h3>
                                @if($criteria->category_document->title_kk != $criteria->category_document->title_ru)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1">
                                        {{ $criteria->category_document->title_kk }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="{{ $this->getCriteriaStatusColor($criteria->application_status->value) }}
                                        px-2 py-2 rounded-full text-xs font-medium  my-3 flex-shrink-0">
                            <i class="{{ $this->getCriteriaStatusIcon($criteria->application_status->value) }} mr-1"></i>
                            {{ $criteria->application_status->title_ru }}
                        </div>

                        <!-- Application Info -->
                        <div class="space-y-3">
                            <div class="flex items-center text-sm">
                                <i class="fas fa-file-alt text-gray-400 mr-2 w-4"></i>
                                <span class="text-gray-600 dark:text-gray-400 truncate">
                                    {{ $criteria->application->licence->title_ru }}
                                </span>
                            </div>

                            <div class="flex items-center text-sm">
                                <i class="fas fa-building text-gray-400 mr-2 w-4"></i>
                                <span class="text-gray-600 dark:text-gray-400 truncate">
                                    {{ $criteria->application->club->short_name_ru }}
                                </span>
                            </div>

                            @if($criteria->application->licence->season)
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-calendar text-gray-400 mr-2 w-4"></i>
                                    <span class="text-gray-600 dark:text-gray-400">
                                        {{ $criteria->application->licence->season->title_ru }}
                                    </span>
                                </div>
                            @endif

                            @if($criteria->application->licence->league)
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-trophy text-gray-400 mr-2 w-4"></i>
                                    <span class="text-gray-600 dark:text-gray-400 truncate">
                                        {{ $criteria->application->licence->league->title_ru }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Readiness Indicator -->
                        <div class="mt-4 pt-4 border-t border-slate-200 dark:border-blue-800/40">
                            <div class="space-y-2">
                                <!-- First Check Status -->
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                        Первичная проверка:
                                    </span>
                                    <div class="flex items-center">
                                        @if($criteria->is_first_passed === true)
                                            <span class="text-xs font-medium text-green-600 dark:text-green-400">
                                                Пройдена
                                            </span>
                                            <i class="fas fa-check text-green-400 ml-1 text-xs"></i>
                                        @elseif($criteria->is_first_passed === false)
                                            <span class="text-xs font-medium text-red-600 dark:text-red-400">
                                                Не пройдена
                                            </span>
                                            <i class="fas fa-times text-red-400 ml-1 text-xs"></i>
                                        @else
                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                                Не проверялась
                                            </span>
                                            <i class="fas fa-clock text-gray-400 ml-1 text-xs"></i>
                                        @endif
                                    </div>
                                </div>

                                <!-- Industry Check Status -->
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                        Отраслевая проверка:
                                    </span>
                                    <div class="flex items-center">
                                        @if($criteria->is_industry_passed === true)
                                            <span class="text-xs font-medium text-green-600 dark:text-green-400">
                                                Пройдена
                                            </span>
                                            <i class="fas fa-check text-green-400 ml-1 text-xs"></i>
                                        @elseif($criteria->is_industry_passed === false)
                                            <span class="text-xs font-medium text-red-600 dark:text-red-400">
                                                Не пройдена
                                            </span>
                                            <i class="fas fa-times text-red-400 ml-1 text-xs"></i>
                                        @else
                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                                Не проверялась
                                            </span>
                                            <i class="fas fa-clock text-gray-400 ml-1 text-xs"></i>
                                        @endif
                                    </div>
                                </div>

                                <!-- Final Check Status -->
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                        Контрольная проверка:
                                    </span>
                                    <div class="flex items-center">
                                        @if($criteria->is_final_passed === true)
                                            <span class="text-xs font-medium text-green-600 dark:text-green-400">
                                                Пройдена
                                            </span>
                                            <i class="fas fa-check text-green-400 ml-1 text-xs"></i>
                                        @elseif($criteria->is_final_passed === false)
                                            <span class="text-xs font-medium text-red-600 dark:text-red-400">
                                                Не пройдена
                                            </span>
                                            <i class="fas fa-times text-red-400 ml-1 text-xs"></i>
                                        @else
                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                                Не проверялась
                                            </span>
                                            <i class="fas fa-clock text-gray-400 ml-1 text-xs"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hover Effect -->
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/0 to-indigo-600/0 group-hover:from-blue-600/10 group-hover:to-indigo-600/5 dark:group-hover:from-blue-500/20 dark:group-hover:to-indigo-500/10 transition-all duration-300 pointer-events-none"></div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($this->getTotalCriterias() > $perPage)
            <div class="mt-8 flex justify-center">
                <nav class="flex items-center space-x-2">
                    <!-- Previous button -->
                    @if($currentPage > 1)
                        <button wire:click="previousPage"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg
                                       hover:bg-gray-50 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-600
                                       dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    @else
                        <button disabled
                                class="px-3 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-300 rounded-lg
                                       cursor-not-allowed dark:bg-gray-800 dark:border-gray-600 dark:text-gray-600">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    @endif

                    <!-- Page numbers -->
                    @for($i = 1; $i <= $this->getLastPage(); $i++)
                        @if($i == $currentPage)
                            <button wire:click="goToPage({{ $i }})"
                                    class="px-3 py-2 text-sm font-medium text-blue-600 bg-blue-100 border border-blue-300 rounded-lg
                                           dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-600">
                                {{ $i }}
                            </button>
                        @elseif(abs($i - $currentPage) <= 2 || $i == 1 || $i == $this->getLastPage())
                            <button wire:click="goToPage({{ $i }})"
                                    class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg
                                           hover:bg-gray-50 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-600
                                           dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300">
                                {{ $i }}
                            </button>
                        @elseif(abs($i - $currentPage) == 3)
                            <span class="px-3 py-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                                ...
                            </span>
                        @endif
                    @endfor

                    <!-- Next button -->
                    @if($currentPage < $this->getLastPage())
                        <button wire:click="nextPage"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg
                                       hover:bg-gray-50 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-600
                                       dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    @else
                        <button disabled
                                class="px-3 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-300 rounded-lg
                                       cursor-not-allowed dark:bg-gray-800 dark:border-gray-600 dark:text-gray-600">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif
                </nav>
            </div>

            <!-- Results info -->
            <div class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
                Показано {{ $criterias->count() }} из {{ $this->getTotalCriterias() }} критериев
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-12">
            <div class="text-center">
                <div class="bg-gray-100 dark:bg-gray-700 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clipboard-list text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                    Критерии не найдены
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    @if($search)
                        По вашему запросу ничего не найдено. Попробуйте изменить параметры поиска.
                    @else
                        В этой категории пока нет критериев для проверки.
                    @endif
                </p>
                @if($search)
                    <button wire:click="set('search', '')"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg
                                   transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Очистить поиск
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
