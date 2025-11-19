<div>
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Управление соревнованиями
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Создание и управление футбольными соревнованиями
                    </p>
                </div>
                @if($canCreateLeagues)
                    <button wire:click="openCreateLeagueModal"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Создать соревнование
                    </button>
                @endif
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Leagues -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 rounded-lg p-3">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Всего соревнований</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $leagues->total() }}</p>
                    </div>
                </div>
            </div>

            <!-- Active Leagues -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-lg p-3">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Активные соревнования</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ \App\Models\League::where('is_active', true)->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Inactive Leagues -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg p-3">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Неактивные соревнования</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ \App\Models\League::where('is_active', false)->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Поиск соревнований
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text"
                               wire:model.live.debounce.500ms="search"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Поиск по названию или описанию...">
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Фильтр по статусу
                    </label>
                    <select wire:model.live="filterStatus"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Все соревнования</option>
                        <option value="1">Активные</option>
                        <option value="0">Неактивные</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session()->has('message'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-green-700 dark:text-green-300">{{ session('message') }}</span>
                </div>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-red-700 dark:text-red-300">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Leagues Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Соревнования
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Описание
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Статус
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Создана
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Действия
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($leagues as $league)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $league->title_ru }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $league->title_kk }} @if($league->title_en) · {{ $league->title_en }} @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm text-gray-900 dark:text-white line-clamp-2">
                                            {{ Str::limit($league->description_ru, 60) }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ Str::limit($league->description_kk, 40) }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($league->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Активна
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Неактивна
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $league->created_at?->format('d.m.Y') ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        @if($canEditLeagues)
                                            <button wire:click="editLeague({{ $league->id }})"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                    title="Редактировать">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>

                                            <button wire:click="toggleLeagueStatus({{ $league->id }})"
                                                    class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                                    title="{{ $league->is_active ? 'Деактивировать' : 'Активировать' }}">
                                                @if($league->is_active)
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @endif
                                            </button>
                                        @endif

                                        @if($canDeleteLeagues)
                                            <button wire:click="deleteLeague({{ $league->id }})"
                                                    wire:confirm="Вы уверены, что хотите удалить это соревнование?"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                    title="Удалить">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Соревания не найдены</h3>
                                        <p class="text-gray-500 dark:text-gray-400">
                                            @if($search || $filterStatus)
                                                По вашему запросу ничего не найдено. Попробуйте изменить параметры поиска.
                                            @else
                                                Соревнования пока не созданы. Создайте первые соревнования для начала работы.
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($leagues->hasPages())
                <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button wire:click="gotoPage('{{ $leagues->previousPageUrl() }}')"
                                @disabled($leagues->onFirstPage())
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Предыдущая
                        </button>
                        <button wire:click="gotoPage('{{ $leagues->nextPageUrl() }}')"
                                @disabled($leagues->onLastPage())
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Следующая
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Показано с <span class="font-medium">{{ $leagues->firstItem() }}</span>
                                по <span class="font-medium">{{ $leagues->lastItem() }}</span>
                                из <span class="font-medium">{{ $leagues->total() }}</span> результатов
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                @if($leagues->onFirstPage())
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-400 cursor-not-allowed">
                                        Предыдущая
                                    </span>
                                @else
                                    <button wire:click="gotoPage('{{ $leagues->previousPageUrl() }}')"
                                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        Предыдущая
                                    </button>
                                @endif

                                @foreach($leagues->links()[0] as $link)
                                    @if($link['active'])
                                        <span aria-current="page" class="relative inline-flex items-center px-4 py-2 border border-blue-500 bg-blue-50 text-sm font-medium text-blue-600">
                                            {{ $link['label'] }}
                                        </span>
                                    @else
                                        @if($link['url'])
                                            <button wire:click="gotoPage('{{ $link['url'] }}')"
                                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                                {{ $link['label'] }}
                                            </button>
                                        @else
                                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                                {{ $link['label'] }}
                                            </span>
                                        @endif
                                    @endif
                                @endforeach

                                @if($leagues->onLastPage())
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-400 cursor-not-allowed">
                                        Следующая
                                    </span>
                                @else
                                    <button wire:click="gotoPage('{{ $leagues->nextPageUrl() }}')"
                                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        Следующая
                                    </button>
                                @endif
                            </nav>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Create League Modal -->
    @if($showCreateLeagueModal)
        <div class="fixed inset-0 z-[9999] bg-black bg-opacity-50 flex items-center justify-center p-4" wire:click.self="closeCreateLeagueModal">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Создание нового соревнования
                        </h3>
                        <button wire:click="closeCreateLeagueModal"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <form wire:submit="createLeague" class="p-6 space-y-6">
                    <!-- Russian Fields -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Русский язык</h4>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Название соревнования (RU) *
                            </label>
                            <input type="text"
                                   wire:model="titleRu"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Например: Премьер-Лига">
                            @error('titleRu')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Описание соревнования (RU) *
                            </label>
                            <textarea wire:model="descriptionRu"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Описание на русском языке"></textarea>
                            @error('descriptionRu')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kazakh Fields -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Қазақ тілі</h4>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Атауы (KK) *
                            </label>
                            <input type="text"
                                   wire:model="titleKk"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Мысалы: Премьер-Лигасы">
                            @error('titleKk')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Сипаттамасы (KK) *
                            </label>
                            <textarea wire:model="descriptionKk"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Сипаттамасы қазақ тілінде"></textarea>
                            @error('descriptionKk')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- English Fields -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">English</h4>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Name (EN)
                            </label>
                            <input type="text"
                                   wire:model="titleEn"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="e.g., Premier League">
                            @error('titleEn')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Description (EN)
                            </label>
                            <textarea wire:model="descriptionEn"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="description in English"></textarea>
                            @error('descriptionEn')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Level Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Уровень соревнования (1-10) *
                        </label>
                        <input type="number"
                               wire:model="level"
                               value="1"
                               min="1"
                               max="10"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Например: 1">
                        @error('level')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Toggle -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="isActive" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Активные соревнования</span>
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button"
                                wire:click="closeCreateLeagueModal"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            Отмена
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            Создать соревнование
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Edit League Modal -->
    @if($showEditLeagueModal)
        <div class="fixed inset-0 z-[9999] bg-black bg-opacity-50 flex items-center justify-center p-4" wire:click.self="closeEditLeagueModal">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Редактирование соревнования
                        </h3>
                        <button wire:click="closeEditLeagueModal"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <form wire:submit="updateLeague" class="p-6 space-y-6">
                    <!-- Russian Fields -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Русский язык</h4>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Название соревнования (RU) *
                            </label>
                            <input type="text"
                                   wire:model="titleRu"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Например: Премьер-Лига">
                            @error('titleRu')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Описание соревнования (RU) *
                            </label>
                            <textarea wire:model="descriptionRu"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Описание соревнований на русском языке"></textarea>
                            @error('descriptionRu')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kazakh Fields -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Қазақ тілі</h4>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Атауы (KK) *
                            </label>
                            <input type="text"
                                   wire:model="titleKk"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Мысалы: Премьер-Лигасы">
                            @error('titleKk')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Сипаттамасы (KK) *
                            </label>
                            <textarea wire:model="descriptionKk"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="сипаттамасы қазақ тілінде"></textarea>
                            @error('descriptionKk')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- English Fields -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">English</h4>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Name (EN)
                            </label>
                            <input type="text"
                                   wire:model="titleEn"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="e.g., Premier League">
                            @error('titleEn')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Description (EN)
                            </label>
                            <textarea wire:model="descriptionEn"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="description in English"></textarea>
                            @error('descriptionEn')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Level Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Уровень соревнований (1-10) *
                        </label>
                        <input type="number"
                               wire:model="level"
                               min="1"
                               max="10"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Например: 1">
                        @error('level')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Toggle -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="isActive" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Активные соревнования</span>
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button"
                                wire:click="closeEditLeagueModal"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            Отмена
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            Сохранить изменения
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
