<div>
    <div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            Управление сезонами
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Создание, редактирование и управление футбольными сезонами
        </p>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 dark:text-green-400 mr-3"></i>
                <p class="text-green-800 dark:text-green-200">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 mr-3"></i>
                <p class="text-red-800 dark:text-red-200">{{ session('error') }}</p>
            </div>
        </div>
    @endif

  
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Всего сезонов</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $seasons->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Активные</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $seasons->where('is_active', true)->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <i class="fas fa-pause-circle text-gray-600 dark:text-gray-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Неактивные</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $seasons->where('is_active', false)->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input
                        type="text"
                        wire:model.live.debounce.500ms="search"
                        placeholder="Название, описание, значение..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400"
                    >
                </div>
            </div>

            <!-- Status Filter -->
            <div class="w-full lg:w-48">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-toggle-on text-gray-400"></i>
                    </div>
                    <select
                        wire:model.live="filterStatus"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 appearance-none"
                    >
                        <option value="">Все статусы</option>
                        <option value="1">Активные</option>
                        <option value="0">Неактивные</option>
                    </select>
                </div>
            </div>

            <!-- Create Button -->
            @if ($canCreateSeasons)
                <button
                    wire:click="openCreateSeasonModal"
                    class="w-full lg:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-200 flex items-center justify-center"
                >
                    <i class="fas fa-plus mr-2"></i>
                    Создать сезон
                </button>
            @endif
        </div>
    </div>

    <!-- Seasons Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <i class="fas fa-calendar mr-2"></i>
                                Сезон
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <i class="fas fa-info-circle mr-2"></i>
                                Описание
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <i class="fas fa-calendar-week mr-2"></i>
                                Даты
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <i class="fas fa-toggle-on mr-2"></i>
                                Статус
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <div class="flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <i class="fas fa-cogs mr-2"></i>
                                Действия
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($seasons as $season)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                        <i class="fas fa-calendar text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $season->title_ru }}
                                            @if($season->title_kk && $season->title_kk !== $season->title_ru)
                                                <span class="text-gray-500 dark:text-gray-400">({{ $season->title_kk }})</span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $season->value }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    @if($season->title_en && $season->title_en !== $season->title_ru)
                                        {{ $season->title_en }}
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    @if($season->start && $season->end)
                                        <div>{{ $season->start->format('d.m.Y') }} - {{ $season->end->format('d.m.Y') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $season->start->diffInDays($season->end) }} дней
                                        </div>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($season->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Активен
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        <i class="fas fa-pause-circle mr-1"></i>
                                        Неактивен
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    @if ($canEditSeasons)
                                        <button
                                            wire:click="editSeason({{ $season->id }})"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="Редактировать"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button
                                            wire:click="toggleSeasonStatus({{ $season->id }})"
                                            class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                            title="Изменить статус"
                                        >
                                            @if($season->is_active)
                                                <i class="fas fa-eye-slash"></i>
                                            @else
                                                <i class="fas fa-eye"></i>
                                            @endif
                                        </button>
                                    @endif

                                    @if ($canDeleteSeasons)
                                        <button
                                            wire:click="deleteSeason({{ $season->id }})"
                                            wire:confirm="Вы уверены, что хотите удалить этот сезон?"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                            title="Удалить"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-calendar-times text-4xl mb-4"></i>
                                    <p class="text-lg">Сезоны не найдены</p>
                                    <p class="text-sm mt-2">Попробуйте изменить параметры поиска или создайте новый сезон.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($seasons->hasPages())
            <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button
                            wire:click="previousPage"
                            disabled="{{ $seasons->onFirstPage() }}"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Предыдущая
                        </button>
                        <button
                            wire:click="nextPage"
                            disabled="{{ $seasons->onLastPage() }}"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Следующая
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Показано с <span class="font-medium">{{ $seasons->firstItem() }}</span> до
                                <span class="font-medium">{{ $seasons->lastItem() }}</span> из
                                <span class="font-medium">{{ $seasons->total() }}</span> записей
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                {{-- Previous Page Link --}}
                                @if($seasons->currentPage() > 1)
                                    <button
                                        wire:click="gotoPage({{ $seasons->currentPage() - 1 }})"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700"
                                    >
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                @else
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-sm font-medium text-gray-300 dark:text-gray-500 cursor-not-allowed">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach($seasons->links()->elements as $element)
                                    @if(is_string($element))
                                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $element }}
                                        </span>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if(!$seasons->onLastPage())
                                    <button
                                        wire:click="gotoPage({{ $seasons->currentPage() + 1 }})"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700"
                                    >
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                @else
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-sm font-medium text-gray-300 dark:text-gray-500 cursor-not-allowed">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                @endif
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Create Season Modal -->
    @if($showCreateSeasonModal)
        <div class="fixed inset-0 z-[9999] bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-2xl w-full mx-4 relative z-[10000] max-h-[90vh] overflow-y-auto">
                <div class="mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-2">
                        Создание нового сезона
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Заполните информацию о новом футбольном сезоне
                    </p>
                </div>

                <form wire:submit="createSeason">
                    <!-- Russian Title -->
                    <div class="mb-4">
                        <label for="titleRu" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Название на русском <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="titleRu"
                            wire:model="titleRu"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Например: 2026-2027"
                            required
                        />
                        @error('titleRu')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kazakh Title -->
                    <div class="mb-4">
                        <label for="titleKk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Атауы қазақша <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="titleKk"
                            wire:model="titleKk"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Мысалы: 2026-2027"
                            required
                        />
                        @error('titleKk')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- English Title -->
                    <div class="mb-4">
                        <label for="titleEn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            English Title
                        </label>
                        <input
                            type="text"
                            id="titleEn"
                            wire:model="titleEn"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="For example: 2026-2027"
                        />
                        @error('titleEn')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dates Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Start Date -->
                        <div>
                            <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Дата начала <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="startDate"
                                wire:model="startDate"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                required
                            />
                            @error('startDate')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Дата окончания <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="endDate"
                                wire:model="endDate"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                required
                            />
                            @error('endDate')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                wire:model="isActive"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-400 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            />
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                Активный сезон
                            </span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Только один сезон может быть активным одновременно
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            wire:click="closeCreateSeasonModal"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Отмена
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove>Создать сезон</span>
                            <span wire:loading>Создание...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Edit Season Modal -->
    @if($showEditSeasonModal)
        <div class="fixed inset-0 z-[9999] bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-2xl w-full mx-4 relative z-[10000] max-h-[90vh] overflow-y-auto">
                <div class="mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-2">
                        Редактирование сезона
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Измените информацию о футбольном сезоне
                    </p>
                </div>

                <form wire:submit="updateSeason">
                    <!-- Russian Title -->
                    <div class="mb-4">
                        <label for="editTitleRu" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Название на русском <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="editTitleRu"
                            wire:model="titleRu"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Например: 2026-2027"
                            required
                        />
                        @error('titleRu')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kazakh Title -->
                    <div class="mb-4">
                        <label for="editTitleKk" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Атауы қазақша <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="editTitleKk"
                            wire:model="titleKk"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Мысалы: 2026-2027"
                            required
                        />
                        @error('titleKk')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- English Title -->
                    <div class="mb-4">
                        <label for="editTitleEn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            English Title
                        </label>
                        <input
                            type="text"
                            id="editTitleEn"
                            wire:model="titleEn"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="For example: 2026-2027"
                        />
                        @error('titleEn')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dates Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Start Date -->
                        <div>
                            <label for="editStartDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Дата начала <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="editStartDate"
                                wire:model="startDate"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                required
                            />
                            @error('startDate')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="editEndDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Дата окончания <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                id="editEndDate"
                                wire:model="endDate"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                required
                            />
                            @error('endDate')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                wire:model="isActive"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-400 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            />
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                Активный сезон
                            </span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Только один сезон может быть активным одновременно
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            wire:click="closeEditSeasonModal"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Отмена
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove>Сохранить изменения</span>
                            <span wire:loading>Сохранение...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>