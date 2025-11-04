<div class="min-h-screen bg-gradient-to-br from-gray-50 via-purple-50 to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Enhanced Header with Gradient -->
        <div class="mb-8">
            <div class="relative overflow-hidden bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 dark:from-purple-700 dark:via-indigo-700 dark:to-blue-700 rounded-2xl shadow-2xl p-8">
                <div class="absolute inset-0 bg-black opacity-5"></div>
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white rounded-full opacity-5"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 bg-white rounded-full opacity-5"></div>

                <div class="relative">
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-certificate text-3xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-black text-white">Мои лицензии</h1>
                            <p class="text-purple-100 text-lg font-medium mt-1">Управление лицензиями футбольных клубов</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-2">
            <div class="flex space-x-2">
                <button wire:click="switchTab('active')"
                        class="flex-1 relative px-6 py-4 rounded-xl font-bold transition-all duration-300 {{ $activeTab === 'active' ? 'bg-gradient-to-r from-purple-500 to-indigo-600 text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <span class="flex items-center justify-center">
                        <i class="fas fa-star mr-2"></i>
                        Активные лицензии
                        @if($activeTab === 'active')
                        <span class="ml-2 px-2.5 py-0.5 bg-white/30 rounded-full text-xs font-bold">
                            {{ $licences->total() }}
                        </span>
                        @endif
                    </span>
                </button>

                <button wire:click="switchTab('all')"
                        class="flex-1 relative px-6 py-4 rounded-xl font-bold transition-all duration-300 {{ $activeTab === 'all' ? 'bg-gradient-to-r from-blue-500 to-cyan-600 text-white shadow-lg' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <span class="flex items-center justify-center">
                        <i class="fas fa-list mr-2"></i>
                        Все лицензии
                        @if($activeTab === 'all')
                        <span class="ml-2 px-2.5 py-0.5 bg-white/30 rounded-full text-xs font-bold">
                            {{ $licences->total() }}
                        </span>
                        @endif
                    </span>
                </button>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="relative">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                    <i class="fas fa-search mr-2 text-purple-500"></i>
                    Поиск лицензий
                </label>
                <div class="relative">
                    <input type="text"
                           wire:model.live.debounce.500ms="search"
                           placeholder="Название лицензии..."
                           class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 dark:focus:ring-purple-400/50 transition-all text-base">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
        </div>

        @if($licences->count() > 0)
        <!-- Licences Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($licences as $licence)
            <a href="{{ route('club.licence-detail', $licence->id) }}"
               class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl hover:shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500 transform hover:-translate-y-2 cursor-pointer">

                <!-- Gradient Header -->
                <div class="relative h-32 bg-gradient-to-br from-purple-500 via-indigo-500 to-blue-500 dark:from-purple-600 dark:via-indigo-600 dark:to-blue-600 overflow-hidden">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-certificate text-6xl text-white opacity-20"></i>
                    </div>

                    <!-- Active Badge -->
                    @if($licence->is_active)
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-500 text-white shadow-lg backdrop-blur-sm">
                            <i class="fas fa-check-circle mr-1.5"></i>
                            Активна
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="p-6">
                    <h3 class="text-xl font-black text-gray-900 dark:text-gray-100 mb-2 line-clamp-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                        {{ $licence->title_ru }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-1">
                        {{ $licence->title_kk }}
                    </p>

                    <!-- Info Grid -->
                    <div class="space-y-3 mb-4">
                        @if($licence->season)
                        <div class="flex items-center group/item">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center group-hover/item:bg-purple-200 dark:group-hover/item:bg-purple-900/50 transition-colors">
                                <i class="fas fa-calendar-alt text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <span class="ml-3 text-sm text-gray-600 dark:text-gray-400 font-medium">{{ $licence->season->title_ru }}</span>
                        </div>
                        @endif

                        @if($licence->league)
                        <div class="flex items-center group/item">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center group-hover/item:bg-indigo-200 dark:group-hover/item:bg-indigo-900/50 transition-colors">
                                <i class="fas fa-trophy text-indigo-600 dark:text-indigo-400"></i>
                            </div>
                            <span class="ml-3 text-sm text-gray-600 dark:text-gray-400 font-medium">{{ $licence->league->title_ru }}</span>
                        </div>
                        @endif

                        <div class="flex items-center group/item">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center group-hover/item:bg-blue-200 dark:group-hover/item:bg-blue-900/50 transition-colors">
                                <i class="fas fa-clock text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Период действия</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300 font-semibold">
                                    {{ $licence->start_at->format('d.m.Y') }} - {{ $licence->end_at->format('d.m.Y') }}
                                </p>
                            </div>
                        </div>

                        @if($licence->licence_deadlines->count() > 0)
                        @php
                            $deadline = $licence->licence_deadlines->first();
                            $statusBadge = $this->getDeadlineStatusBadge($deadline);
                        @endphp

                        <!-- Deadline Status Badge -->
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between p-3 rounded-lg {{ $statusBadge['classes'] }}">
                                <div class="flex items-center">
                                    <i class="fas {{ $statusBadge['icon'] }} text-lg mr-2"></i>
                                    <span class="text-sm font-bold">{{ $statusBadge['text'] }}</span>
                                </div>
                            </div>

                            <!-- Deadline Date -->
                            <div class="mt-2 flex items-center text-xs text-gray-600 dark:text-gray-400">
                                <i class="fas fa-calendar-times mr-1.5"></i>
                                <span>Дедлайн: {{ $deadline->end_at->format('d.m.Y H:i') }}</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Description -->
                    @if($licence->description_ru)
                    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                            {{ $licence->description_ru }}
                        </p>
                    </div>
                    @endif

                    <!-- View Button -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="inline-flex items-center text-purple-600 dark:text-purple-400 font-bold group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                            Просмотреть детали
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($licences->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $licences->links('pagination::livewire-tailwind') }}
        </div>
        @endif
        @else
        <!-- Empty State -->
        <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700">
            <div class="flex flex-col items-center">
                <div class="w-32 h-32 bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-900/30 dark:to-indigo-900/30 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-certificate text-6xl text-purple-400 dark:text-purple-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-2">
                    @if($activeTab === 'active')
                    Активные лицензии не найдены
                    @else
                    Лицензии не найдены
                    @endif
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md">
                    @if($activeTab === 'active')
                    У вас пока нет активных лицензий с дедлайнами для ваших клубов
                    @else
                    Попробуйте изменить параметры поиска или проверьте позже
                    @endif
                </p>
                @if($activeTab === 'active')
                <button wire:click="switchTab('all')"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white font-bold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-list mr-2"></i>
                    Посмотреть все лицензии
                </button>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
