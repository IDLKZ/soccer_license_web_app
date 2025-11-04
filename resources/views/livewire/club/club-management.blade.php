<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Управление клубами</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Создание, редактирование и управление вашими клубами</p>
        </div>
        @if($canCreate)
        <button wire:click="$set('showCreateModal', true)"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors duration-150">
            <i class="fas fa-plus mr-2"></i>
            Добавить клуб
        </button>
        @endif
    </div>

    <!-- Success Messages -->
    @if(session()->has('message'))
    <div class="mb-4 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 dark:border-green-400 p-4 rounded">
        <div class="flex">
            <i class="fas fa-check-circle text-green-500 dark:text-green-400 mt-0.5"></i>
            <p class="ml-3 text-green-700 dark:text-green-300">{{ session('message') }}</p>
        </div>
    </div>
    @endif

    <!-- Error Messages -->
    @if(session()->has('error'))
    <div class="mb-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-400 p-4 rounded">
        <div class="flex">
            <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 mt-0.5"></i>
            <p class="ml-3 text-red-700 dark:text-red-300">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Поиск и фильтры -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-search mr-1 text-gray-400 dark:text-gray-500"></i>
                    Поиск
                </label>
                <input type="text"
                       wire:model.live.debounce.500ms="search"
                       placeholder="Название, БИН, email..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-tag mr-1 text-gray-400 dark:text-gray-500"></i>
                    Тип клуба
                </label>
                <select wire:model.live="filterType"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                    <option value="">Все типы</option>
                    @foreach($clubTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if($clubs->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg dark:shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-building mr-1 text-gray-400 dark:text-gray-500"></i>
                                Клуб
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle mr-1 text-gray-400 dark:text-gray-500"></i>
                                Информация
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-tag mr-1 text-gray-400 dark:text-gray-500"></i>
                                Тип клуба
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-user-tie mr-1 text-gray-400 dark:text-gray-500"></i>
                                Администратор
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-check-circle mr-1 text-gray-400 dark:text-gray-500"></i>
                                Статус
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-cogs mr-1 text-gray-400 dark:text-gray-500"></i>
                                Действия
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($clubs as $club)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-4">
                            <div class="flex items-center">
                                @if($club->image_url)
                                <img src="{{ $club->image_url }}" alt="{{ $club->full_name_ru }}" class="h-10 w-10 rounded-full mr-3 object-cover">
                                @else
                                <div class="h-10 w-10 rounded-full mr-3 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <i class="fas fa-building text-white text-sm"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $club->short_name_ru }}
                                        <span class="text-gray-400 dark:text-gray-500 ml-1">{{ $club->short_name_kk }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        БИН: {{ $club->bin }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                {{ Str::limit($club->full_name_ru, 40) }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $club->legal_address }}
                            </div>
                            @if($club->email || $club->phone_number)
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                @if($club->email)<i class="fas fa-envelope mr-1"></i>{{ $club->email }}@endif
                                @if($club->phone_number)<i class="fas fa-phone ml-2 mr-1"></i>{{ $club->phone_number }}@endif
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @if($club->club_type)
                            <div class="text-sm">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800 dark:from-purple-900/50 dark:to-pink-900/50 dark:text-purple-300">
                                    {{ $club->club_type->title_ru }}
                                </span>
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @if($club->club_teams && $club->club_teams->first())
                            @php
                                $admin = $club->club_teams->first()->user;
                            @endphp
                            @if($admin)
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full mr-2 bg-gradient-to-br from-green-500 to-blue-500 flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">
                                        {{ mb_substr($admin->first_name, 0, 1) }}{{ mb_substr($admin->last_name ?? '', 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $admin->first_name }} {{ $admin->last_name ?? '' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $admin->email }}
                                    </div>
                                </div>
                            </div>
                            @endif
                            @else
                            <span class="text-sm text-gray-400 dark:text-gray-500">
                                <i class="fas fa-user-slash mr-1"></i>
                                Не назначен
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($club->verified)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 dark:from-green-900/50 dark:to-emerald-900/50 dark:text-green-300">
                                <i class="fas fa-check-circle mr-1"></i>
                                Верифицирован
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-yellow-100 to-orange-100 text-yellow-800 dark:from-yellow-900/50 dark:to-orange-900/50 dark:text-yellow-300">
                                <i class="fas fa-clock mr-1"></i>
                                На проверке
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                @if($canEdit)
                                <button wire:click="editClub({{ $club->id }})"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                        title="Редактировать">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @endif
                                @if($canDelete)
                                    @if(auth()->user() && auth()->user()->role && !auth()->user()->role->is_administrative)
                                        {{-- Club members can leave the club --}}
                                        <button wire:click="leaveClub({{ $club->id }})"
                                                wire:confirm="Вы уверены, что хотите выйти из этого клуба?"
                                                class="text-orange-600 hover:text-orange-800 dark:text-orange-400 dark:hover:text-orange-300 transition-colors"
                                                title="Выйти из клуба">
                                            <i class="fas fa-sign-out-alt"></i>
                                        </button>
                                    @else
                                        {{-- Admin can delete the club --}}
                                        <button wire:click="deleteClub({{ $club->id }})"
                                                wire:confirm="Вы уверены, что хотите удалить этот клуб?"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                                title="Удалить клуб">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $clubs->links() }}
        </div>
    </div>
    @else
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg border border-gray-200 dark:border-gray-700 p-12 text-center">
        <div class="mx-auto h-20 w-20 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
            <i class="fas fa-building text-gray-400 dark:text-gray-500 text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
            Клубы не найдены
        </h3>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
            У вас пока нет добавленных клубов или по вашему запросу ничего не найдено.
        </p>
        @if($canCreate)
        <button wire:click="$set('showCreateModal', true)"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors duration-150">
            <i class="fas fa-plus mr-2"></i>
            Добавить первый клуб
        </button>
        @endif
    </div>
    @endif

    <!-- Create Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-80 transition-opacity" wire:click="closeCreateModal"></div>

            <!-- Center modal -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full max-h-[90vh]">
                <form wire:submit="createClub">
                    <!-- Modal Content with Scroll -->
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[calc(90vh-120px)] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-200 dark:scrollbar-track-gray-800 hover:scrollbar-thumb-gray-500 dark:hover:scrollbar-thumb-gray-500">
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Создание нового клуба
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Заполните информацию о клубе
                            </p>
                        </div>

                        <!-- Logo Upload Section -->
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600 pb-2 mb-4">
                                <i class="fas fa-image mr-1"></i>
                                Логотип клуба
                            </h4>
                            <div class="flex items-center space-x-4">
                                <!-- Logo Preview -->
                                <div class="flex-shrink-0">
                                    @if($clubLogo)
                                        <img src="{{ $clubLogo->temporaryUrl() }}" alt="Preview" class="h-24 w-24 rounded-lg object-cover border-2 border-blue-500 shadow-lg">
                                    @else
                                        <div class="h-24 w-24 rounded-lg bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 flex items-center justify-center border-2 border-dashed border-gray-400 dark:border-gray-500">
                                            <i class="fas fa-image text-gray-400 dark:text-gray-500 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <!-- Upload Button -->
                                <div class="flex-1">
                                    <label class="block">
                                        <span class="sr-only">Выберите логотип</span>
                                        <input type="file" wire:model="clubLogo" accept="image/*"
                                               class="block w-full text-sm text-gray-500 dark:text-gray-400
                                                      file:mr-4 file:py-2 file:px-4
                                                      file:rounded-lg file:border-0
                                                      file:text-sm file:font-semibold
                                                      file:bg-blue-50 file:text-blue-700
                                                      dark:file:bg-blue-900/50 dark:file:text-blue-300
                                                      hover:file:bg-blue-100 dark:hover:file:bg-blue-900
                                                      file:cursor-pointer cursor-pointer
                                                      transition-colors">
                                    </label>
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF до 2MB</p>
                                    @error('clubLogo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    <div wire:loading wire:target="clubLogo" class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                        <i class="fas fa-spinner fa-spin mr-1"></i>Загрузка...
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Названия клубов -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600 pb-2">
                                    <i class="fas fa-tag mr-1"></i>
                                    Названия клуба
                                </h4>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Полное название (RU) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="fullNameRu"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('fullNameRu') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Полное название (KK) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="fullNameKk"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('fullNameKk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Полное название (EN)
                                    </label>
                                    <input type="text" wire:model="fullNameEn"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('fullNameEn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Краткое название (RU) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="shortNameRu"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('shortNameRu') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Краткое название (KK) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="shortNameKk"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('shortNameKk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Краткое название (EN)
                                    </label>
                                    <input type="text" wire:model="shortNameEn"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('shortNameEn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Основная информация -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600 pb-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Основная информация
                                </h4>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        БИН <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="bin"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('bin') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Дата основания <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" wire:model="foundationDate"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('foundationDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Юридический адрес <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="legalAddress"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('legalAddress') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Фактический адрес <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="actualAddress"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('actualAddress') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Email
                                    </label>
                                    <input type="email" wire:model="email"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Телефон
                                    </label>
                                    <input type="text" wire:model="phoneNumber"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('phoneNumber') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Веб-сайт
                                    </label>
                                    <input type="url" wire:model="website"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('website') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <!-- Тип и лига -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600 pb-2">
                                    <i class="fas fa-layer-group mr-1"></i>
                                    Классификация
                                </h4>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Тип клуба <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="typeId"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                        <option value="">Выберите тип</option>
                                        @foreach($clubTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                                        @endforeach
                                    </select>
                                    @error('typeId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Администратор клуба <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="administratorId"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                        <option value="">Выберите администратора</option>
                                        @foreach($administrators as $admin)
                                        <option value="{{ $admin->id }}">{{ $admin->first_name }} {{ $admin->last_name ?? '' }} ({{ $admin->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('administratorId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Описание -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600 pb-2">
                                    <i class="fas fa-align-left mr-1"></i>
                                    Описание клуба
                                </h4>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (RU)
                                    </label>
                                    <textarea wire:model="descriptionRu" rows="4"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"></textarea>
                                    @error('descriptionRu') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (KK)
                                    </label>
                                    <textarea wire:model="descriptionKk" rows="4"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"></textarea>
                                    @error('descriptionKk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (EN)
                                    </label>
                                    <textarea wire:model="descriptionEn" rows="4"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"></textarea>
                                    @error('descriptionEn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="verified" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Клуб верифицирован</span>
                            </label>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <i class="fas fa-save mr-2"></i>
                            Создать клуб
                        </button>
                        <button type="button" wire:click="closeCreateModal"
                                class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-80 transition-opacity" wire:click="closeEditModal"></div>

            <!-- Center modal -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full max-h-[90vh]">
                <form wire:submit="updateClub">
                    <!-- Modal Content with Scroll -->
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[calc(90vh-120px)] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-200 dark:scrollbar-track-gray-800 hover:scrollbar-thumb-gray-500 dark:hover:scrollbar-thumb-gray-500">
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Редактирование клуба
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Внесите изменения в информацию о клубе
                            </p>
                        </div>

                        <!-- Logo Upload Section -->
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600 pb-2 mb-4">
                                <i class="fas fa-image mr-1"></i>
                                Логотип клуба
                            </h4>
                            <div class="flex items-center space-x-4">
                                <!-- Logo Preview -->
                                <div class="flex-shrink-0">
                                    @if($clubLogo)
                                        <img src="{{ $clubLogo->temporaryUrl() }}" alt="Preview" class="h-24 w-24 rounded-lg object-cover border-2 border-blue-500 shadow-lg">
                                    @elseif($existingLogoUrl)
                                        <img src="{{ $existingLogoUrl }}" alt="Current Logo" class="h-24 w-24 rounded-lg object-cover border-2 border-gray-300 dark:border-gray-600 shadow-lg">
                                    @else
                                        <div class="h-24 w-24 rounded-lg bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 flex items-center justify-center border-2 border-dashed border-gray-400 dark:border-gray-500">
                                            <i class="fas fa-image text-gray-400 dark:text-gray-500 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <!-- Upload Button -->
                                <div class="flex-1">
                                    <label class="block">
                                        <span class="sr-only">Выберите новый логотип</span>
                                        <input type="file" wire:model="clubLogo" accept="image/*"
                                               class="block w-full text-sm text-gray-500 dark:text-gray-400
                                                      file:mr-4 file:py-2 file:px-4
                                                      file:rounded-lg file:border-0
                                                      file:text-sm file:font-semibold
                                                      file:bg-blue-50 file:text-blue-700
                                                      dark:file:bg-blue-900/50 dark:file:text-blue-300
                                                      hover:file:bg-blue-100 dark:hover:file:bg-blue-900
                                                      file:cursor-pointer cursor-pointer
                                                      transition-colors">
                                    </label>
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF до 2MB</p>
                                    @error('clubLogo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    <div wire:loading wire:target="clubLogo" class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                        <i class="fas fa-spinner fa-spin mr-1"></i>Загрузка...
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Названия клубов -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600 pb-2">
                                    <i class="fas fa-tag mr-1"></i>
                                    Названия клуба
                                </h4>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Полное название (RU) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="fullNameRu"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('fullNameRu') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Полное название (KK) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="fullNameKk"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('fullNameKk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Полное название (EN)
                                    </label>
                                    <input type="text" wire:model="fullNameEn"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('fullNameEn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Краткое название (RU) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="shortNameRu"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('shortNameRu') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Краткое название (KK) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="shortNameKk"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('shortNameKk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Краткое название (EN)
                                    </label>
                                    <input type="text" wire:model="shortNameEn"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('shortNameEn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Основная информация -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600 pb-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Основная информация
                                </h4>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        БИН <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="bin"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('bin') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Дата основания <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" wire:model="foundationDate"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('foundationDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Юридический адрес <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="legalAddress"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('legalAddress') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Фактический адрес <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="actualAddress"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('actualAddress') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Email
                                    </label>
                                    <input type="email" wire:model="email"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Телефон
                                    </label>
                                    <input type="text" wire:model="phoneNumber"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('phoneNumber') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Веб-сайт
                                    </label>
                                    <input type="url" wire:model="website"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                    @error('website') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <!-- Тип и лига -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600 pb-2">
                                    <i class="fas fa-layer-group mr-1"></i>
                                    Классификация
                                </h4>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Тип клуба <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="typeId"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                        <option value="">Выберите тип</option>
                                        @foreach($clubTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                                        @endforeach
                                    </select>
                                    @error('typeId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Администратор клуба <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="administratorId"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                                        <option value="">Выберите администратора</option>
                                        @foreach($administrators as $admin)
                                        <option value="{{ $admin->id }}">{{ $admin->first_name }} {{ $admin->last_name ?? '' }} ({{ $admin->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('administratorId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Описание -->
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-600 pb-2">
                                    <i class="fas fa-align-left mr-1"></i>
                                    Описание клуба
                                </h4>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (RU)
                                    </label>
                                    <textarea wire:model="descriptionRu" rows="4"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"></textarea>
                                    @error('descriptionRu') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (KK)
                                    </label>
                                    <textarea wire:model="descriptionKk" rows="4"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"></textarea>
                                    @error('descriptionKk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (EN)
                                    </label>
                                    <textarea wire:model="descriptionEn" rows="4"
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"></textarea>
                                    @error('descriptionEn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="verified" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Клуб верифицирован</span>
                            </label>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <i class="fas fa-save mr-2"></i>
                            Сохранить изменения
                        </button>
                        <button type="button" wire:click="closeEditModal"
                                class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>