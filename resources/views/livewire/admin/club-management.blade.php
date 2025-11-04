<div>
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Управление клубами
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Создание и управление футбольными клубами
                    </p>
                </div>
                @if($canCreateClubs)
                    <button wire:click="openCreateClubModal"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Создать клуб
                    </button>
                @endif
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Clubs -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 rounded-lg p-3">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Всего клубов</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $clubs->total() }}</p>
                    </div>
                </div>
            </div>

            <!-- Verified Clubs -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-lg p-3">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Верифицированные</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ \App\Models\Club::where('verified', true)->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Unverified Clubs -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900 rounded-lg p-3">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Неверифицированные</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ \App\Models\Club::where('verified', false)->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Club Types -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900 rounded-lg p-3">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Типы клубов</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $clubTypes->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Поиск клубов
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
                               placeholder="Поиск по названию, БИН, email...">
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Фильтр по статусу
                    </label>
                    <select wire:model.live="filterStatus"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Все статусы</option>
                        <option value="1">Верифицированные</option>
                        <option value="0">Неверифицированные</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Фильтр по типу
                    </label>
                    <select wire:model.live="filterType"
                            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Все типы</option>
                        @foreach($clubTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                        @endforeach
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

        <!-- Clubs Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Клуб
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                БИН
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Тип
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Статус
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Создан
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Действия
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($clubs as $club)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @php
                                            $photoUrl = $this->getClubPhotoUrl($club);
                                        @endphp
                                        @if($photoUrl)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ $photoUrl }}" alt="{{ $club->short_name_ru }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                                <span class="text-white font-semibold text-sm">
                                                    {{ mb_substr($club->short_name_ru, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $club->short_name_ru }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $club->short_name_kk }} @if($club->short_name_en) · {{ $club->short_name_en }} @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 font-mono">
                                    {{ $club->bin }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($club->club_type)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                            {{ $club->club_type->title_ru }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($club->verified)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Верифицирован
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
                                            </svg>
                                            Не верифицирован
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $club->created_at->format('d.m.Y') }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        @if($canEditClubs)
                                            <button wire:click="editClub({{ $club->id }})"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                    title="Редактировать">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>

                                            <button wire:click="toggleClubVerification({{ $club->id }})"
                                                    class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                                    title="{{ $club->verified ? 'Снять верификацию' : 'Верифицировать' }}">
                                                @if($club->verified)
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l4 4m-4-4h-4" />
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @endif
                                            </button>
                                        @endif

                                        @if($canDeleteClubs)
                                            <button wire:click="deleteClub({{ $club->id }})"
                                                    wire:confirm="Вы уверены, что хотите удалить этот клуб?"
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
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Клубы не найдены</h3>
                                        <p class="text-gray-500 dark:text-gray-400">
                                            @if($search || $filterStatus || $filterType)
                                                По вашему запросу ничего не найдено. Попробуйте изменить параметры поиска.
                                            @else
                                                Клубы пока не созданы. Создайте первый клуб для начала работы.
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
            @if($clubs->hasPages())
                <div class="bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button wire:click="gotoPage('{{ $clubs->previousPageUrl() }}')"
                                @disabled($clubs->onFirstPage())
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Предыдущая
                        </button>
                        <button wire:click="gotoPage('{{ $clubs->nextPageUrl() }}')"
                                @disabled($clubs->onLastPage())
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Следующая
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Показано с <span class="font-medium">{{ $clubs->firstItem() }}</span>
                                по <span class="font-medium">{{ $clubs->lastItem() }}</span>
                                из <span class="font-medium">{{ $clubs->total() }}</span> результатов
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                @if($clubs->onFirstPage())
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-400 cursor-not-allowed">
                                        Предыдущая
                                    </span>
                                @else
                                    <button wire:click="gotoPage('{{ $clubs->previousPageUrl() }}')"
                                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        Предыдущая
                                    </button>
                                @endif

                                @foreach($clubs->links()[0] as $link)
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

                                @if($clubs->onLastPage())
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-400 cursor-not-allowed">
                                        Следующая
                                    </span>
                                @else
                                    <button wire:click="gotoPage('{{ $clubs->nextPageUrl() }}')"
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

    <!-- Create Club Modal -->
    @if($showCreateClubModal)
        <div class="fixed inset-0 z-[9999] bg-black bg-opacity-50 flex items-center justify-center p-4" wire:click.self="closeCreateClubModal">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Создание нового клуба
                        </h3>
                        <button wire:click="closeCreateClubModal"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <form wire:submit="createClub" class="p-6 space-y-6">
                    <!-- Photo Upload -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Фото клуба</h4>

                        <div class="flex justify-center">
                            <div class="w-32 h-32 rounded-full bg-gray-200 dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center overflow-hidden">
                                @if($photo)
                                    <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                                @else
                                    <div class="text-center">
                                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Логотип</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Логотип клуба (опционально)
                            </label>
                            <input type="file"
                                   wire:model="photo"
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900/20 dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-blue-900/40">
                            @error('photo') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Максимальный размер: 2MB. Форматы: JPG, PNG, GIF</p>
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Основная информация</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Полное название (RU) *
                                </label>
                                <input type="text"
                                       wire:model="fullNameRu"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Например: Футбольный клуб 'Кайрат'">
                                @error('fullNameRu')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Полное название (KK) *
                                </label>
                                <input type="text"
                                       wire:model="fullNameKk"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Мысалы: 'Қайрат' футбол клубы">
                                @error('fullNameKk')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Полное название (EN)
                                </label>
                                <input type="text"
                                       wire:model="fullNameEn"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="e.g., Football Club 'Kairat'">
                                @error('fullNameEn')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    БИН * (12 цифр)
                                </label>
                                <input type="text"
                                       wire:model="bin"
                                       maxlength="12"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white font-mono"
                                       placeholder="XXXXXXXXXXXX">
                                @error('bin')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Короткое название (RU) *
                                </label>
                                <input type="text"
                                       wire:model="shortNameRu"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Кайрат">
                                @error('shortNameRu')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Короткое название (KK) *
                                </label>
                                <input type="text"
                                       wire:model="shortNameKk"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Қайрат">
                                @error('shortNameKk')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Короткое название (EN)
                                </label>
                                <input type="text"
                                       wire:model="shortNameEn"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Kairat">
                                @error('shortNameEn')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Дата основания *
                            </label>
                            <input type="date"
                                   wire:model="foundationDate"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('foundationDate')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Описание (RU)
                            </label>
                            <textarea wire:model="descriptionRu"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Описание клуба на русском языке"></textarea>
                            @error('descriptionRu')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Описание (KK)
                            </label>
                            <textarea wire:model="descriptionKk"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Клуб сипаттамасы қазақ тілінде"></textarea>
                            @error('descriptionKk')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Описание (EN)
                            </label>
                            <textarea wire:model="descriptionEn"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Club description in English"></textarea>
                            @error('descriptionEn')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Контактная информация</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Юридический адрес *
                                </label>
                                <input type="text"
                                       wire:model="legalAddress"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Юридический адрес клуба">
                                @error('legalAddress')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Фактический адрес *
                                </label>
                                <input type="text"
                                       wire:model="actualAddress"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Фактический адрес клуба">
                                @error('actualAddress')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Email
                                </label>
                                <input type="email"
                                       wire:model="email"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="club@example.com">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Телефон
                                </label>
                                <input type="tel"
                                       wire:model="phoneNumber"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="+7 (727) 123-45-67">
                                @error('phoneNumber')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Вебсайт
                                </label>
                                <input type="url"
                                       wire:model="website"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="https://example.com">
                                @error('website')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Дополнительная информация</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Тип клуба
                                </label>
                                <select wire:model="typeId"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Выберите тип клуба</option>
                                    @foreach($clubTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('typeId')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Родительский клуб
                                </label>
                                <select wire:model="parentId"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Нет родительского клуба</option>
                                    @foreach($allClubs as $club)
                                        <option value="{{ $club->id }}">{{ $club->short_name_ru }}</option>
                                    @endforeach
                                </select>
                                @error('parentId')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="verified" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Верифицированный клуб</span>
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button"
                                wire:click="closeCreateClubModal"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            Отмена
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            Создать клуб
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Edit Club Modal -->
    @if($showEditClubModal)
        <div class="fixed inset-0 z-[9999] bg-black bg-opacity-50 flex items-center justify-center p-4" wire:click.self="closeEditClubModal">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Редактирование клуба
                        </h3>
                        <button wire:click="closeEditClubModal"
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <form wire:submit="updateClub" class="p-6 space-y-6">
                    @php
                        $editingClub = \App\Models\Club::find($editingClubId);
                        $currentPhotoUrl = $editingClub ? $this->getClubPhotoUrl($editingClub) : null;
                    @endphp

                    <!-- Photo Upload -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Фото клуба</h4>

                        <div class="flex justify-center">
                            <div class="w-32 h-32 rounded-full bg-gray-200 dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center overflow-hidden">
                                @if($photo)
                                    <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                                @elseif($currentPhotoUrl)
                                    <img src="{{ $currentPhotoUrl }}" alt="Current photo" class="w-full h-full object-cover">
                                @else
                                    <div class="text-center">
                                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Логотип</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Логотип клуба (опционально)
                            </label>
                            <input type="file"
                                   wire:model="photo"
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900/20 dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-blue-900/40">
                            @error('photo') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Максимальный размер: 2MB. Форматы: JPG, PNG, GIF</p>
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Основная информация</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Полное название (RU) *
                                </label>
                                <input type="text"
                                       wire:model="fullNameRu"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Например: Футбольный клуб 'Кайрат'">
                                @error('fullNameRu')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Полное название (KK) *
                                </label>
                                <input type="text"
                                       wire:model="fullNameKk"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Мысалы: 'Қайрат' футбол клубы">
                                @error('fullNameKk')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Полное название (EN)
                                </label>
                                <input type="text"
                                       wire:model="fullNameEn"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="e.g., Football Club 'Kairat'">
                                @error('fullNameEn')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    БИН * (12 цифр)
                                </label>
                                <input type="text"
                                       wire:model="bin"
                                       maxlength="12"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white font-mono"
                                       placeholder="XXXXXXXXXXXX">
                                @error('bin')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Короткое название (RU) *
                                </label>
                                <input type="text"
                                       wire:model="shortNameRu"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Кайрат">
                                @error('shortNameRu')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Короткое название (KK) *
                                </label>
                                <input type="text"
                                       wire:model="shortNameKk"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Қайрат">
                                @error('shortNameKk')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Короткое название (EN)
                                </label>
                                <input type="text"
                                       wire:model="shortNameEn"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Kairat">
                                @error('shortNameEn')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Дата основания *
                            </label>
                            <input type="date"
                                   wire:model="foundationDate"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('foundationDate')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Описание (RU)
                            </label>
                            <textarea wire:model="descriptionRu"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Описание клуба на русском языке"></textarea>
                            @error('descriptionRu')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Описание (KK)
                            </label>
                            <textarea wire:model="descriptionKk"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Клуб сипаттамасы қазақ тілінде"></textarea>
                            @error('descriptionKk')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Описание (EN)
                            </label>
                            <textarea wire:model="descriptionEn"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Club description in English"></textarea>
                            @error('descriptionEn')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Контактная информация</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Юридический адрес *
                                </label>
                                <input type="text"
                                       wire:model="legalAddress"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Юридический адрес клуба">
                                @error('legalAddress')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Фактический адрес *
                                </label>
                                <input type="text"
                                       wire:model="actualAddress"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Фактический адрес клуба">
                                @error('actualAddress')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Email
                                </label>
                                <input type="email"
                                       wire:model="email"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="club@example.com">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Телефон
                                </label>
                                <input type="tel"
                                       wire:model="phoneNumber"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="+7 (727) 123-45-67">
                                @error('phoneNumber')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Вебсайт
                                </label>
                                <input type="url"
                                       wire:model="website"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="https://example.com">
                                @error('website')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Дополнительная информация</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Тип клуба
                                </label>
                                <select wire:model="typeId"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Выберите тип клуба</option>
                                    @foreach($clubTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                                    @endforeach
                                </select>
                                @error('typeId')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Родительский клуб
                                </label>
                                <select wire:model="parentId"
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Нет родительского клуба</option>
                                    @foreach($allClubs as $club)
                                        <option value="{{ $club->id }}">{{ $club->short_name_ru }}</option>
                                    @endforeach
                                </select>
                                @error('parentId')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="verified" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Верифицированный клуб</span>
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button"
                                wire:click="closeEditClubModal"
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