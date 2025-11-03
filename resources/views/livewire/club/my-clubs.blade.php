<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Enhanced Header with Gradient -->
        <div class="mb-8">
            <div class="relative overflow-hidden bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 dark:from-green-700 dark:via-emerald-700 dark:to-teal-700 rounded-2xl shadow-2xl p-8">
                <div class="absolute inset-0 bg-black opacity-5"></div>
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white rounded-full opacity-5"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 bg-white rounded-full opacity-5"></div>

                <div class="relative flex justify-between items-center">
                    <div>
                        <h1 class="text-4xl font-black text-white mb-2 flex items-center">
                            <i class="fas fa-shield-alt mr-4 text-5xl"></i>
                            Мои клубы
                        </h1>
                        <p class="text-green-100 text-lg font-medium">Управление футбольными клубами</p>
                    </div>
                    @if($canCreate)
                    <button wire:click="$set('showCreateModal', true)"
                            class="group relative px-8 py-4 bg-white hover:bg-gray-50 text-green-600 font-bold rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                        <span class="flex items-center">
                            <i class="fas fa-plus-circle mr-3 text-2xl group-hover:rotate-90 transition-transform duration-300"></i>
                            <span class="text-lg">Создать клуб</span>
                        </span>
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Success Messages -->
        @if(session()->has('message'))
        <div class="mb-6 animate-fade-in-down">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-l-4 border-green-500 dark:border-green-400 p-5 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-3xl text-green-500 dark:text-green-400"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-lg font-semibold text-green-800 dark:text-green-300">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Error Messages -->
        @if(session()->has('error'))
        <div class="mb-6 animate-fade-in-down">
            <div class="bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border-l-4 border-red-500 dark:border-red-400 p-5 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-3xl text-red-500 dark:text-red-400"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-lg font-semibold text-red-800 dark:text-red-300">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Enhanced Search and Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 mb-8 backdrop-blur-sm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="relative">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                        <i class="fas fa-search mr-2 text-blue-500"></i>
                        Поиск
                    </label>
                    <div class="relative">
                        <input type="text"
                               wire:model.live.debounce.500ms="search"
                               placeholder="Название, БИН, email..."
                               class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/50 focus:border-blue-500 dark:focus:ring-blue-400/50 transition-all text-base">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div class="relative">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                        <i class="fas fa-filter mr-2 text-purple-500"></i>
                        Тип клуба
                    </label>
                    <div class="relative">
                        <select wire:model.live="filterType"
                                class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 dark:focus:ring-purple-400/50 transition-all text-base appearance-none">
                            <option value="">Все типы</option>
                            @foreach($clubTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-tag absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>
        </div>

        @if($clubs->count() > 0)
        <!-- Enhanced Clubs Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
            @foreach($clubs as $club)
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl hover:shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-500 transform hover:-translate-y-2">
                <!-- Club Image with Overlay -->
                <div class="relative h-56 overflow-hidden">
                    @if($club->image_url)
                    <img src="{{ asset('storage/' . $club->image_url) }}"
                         alt="{{ $club->short_name_ru }}"
                         class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                    <div class="h-full w-full bg-gradient-to-br from-green-500 via-emerald-500 to-teal-500 dark:from-green-600 dark:via-emerald-600 dark:to-teal-600 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-9xl text-white opacity-20"></i>
                    </div>
                    @endif

                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                    <!-- Club Name on Image -->
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <h3 class="text-2xl font-black text-white mb-1 drop-shadow-lg">
                            {{ $club->short_name_ru }}
                        </h3>
                        <p class="text-sm text-green-200 font-medium drop-shadow">
                            {{ $club->short_name_kk }}
                        </p>
                    </div>

                    <!-- Verified Badge -->
                    @if($club->verified)
                    <div class="absolute top-4 right-4">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-500 text-white shadow-lg backdrop-blur-sm">
                            <i class="fas fa-check-circle mr-1.5"></i>
                            Верифицирован
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Club Content -->
                <div class="p-6">
                    <p class="text-base text-gray-700 dark:text-gray-300 mb-4 line-clamp-2 font-medium">
                        {{ $club->full_name_ru }}
                    </p>

                    <!-- Club Info Grid -->
                    <div class="space-y-3 mb-6">
                        @if($club->club_type)
                        <div class="flex items-center group/item">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center group-hover/item:bg-blue-200 dark:group-hover/item:bg-blue-900/50 transition-colors">
                                <i class="fas fa-tag text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <span class="ml-3 text-sm text-gray-600 dark:text-gray-400 font-medium">{{ $club->club_type->title_ru }}</span>
                        </div>
                        @endif

                        <div class="flex items-center group/item">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center group-hover/item:bg-purple-200 dark:group-hover/item:bg-purple-900/50 transition-colors">
                                <i class="fas fa-id-card text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <span class="ml-3 text-sm text-gray-600 dark:text-gray-400 font-medium font-mono">{{ $club->bin }}</span>
                        </div>

                        <div class="flex items-center group/item">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center group-hover/item:bg-orange-200 dark:group-hover/item:bg-orange-900/50 transition-colors">
                                <i class="fas fa-calendar text-orange-600 dark:text-orange-400"></i>
                            </div>
                            <span class="ml-3 text-sm text-gray-600 dark:text-gray-400 font-medium">{{ $club->foundation_date->format('d.m.Y') }}</span>
                        </div>

                        @if($club->email)
                        <div class="flex items-center group/item">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center group-hover/item:bg-green-200 dark:group-hover/item:bg-green-900/50 transition-colors">
                                <i class="fas fa-envelope text-green-600 dark:text-green-400"></i>
                            </div>
                            <span class="ml-3 text-sm text-gray-600 dark:text-gray-400 truncate font-medium">{{ $club->email }}</span>
                        </div>
                        @endif

                        <div class="flex items-center group/item">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center group-hover/item:bg-indigo-200 dark:group-hover/item:bg-indigo-900/50 transition-colors">
                                <i class="fas fa-users text-indigo-600 dark:text-indigo-400"></i>
                            </div>
                            <span class="ml-3 text-sm text-gray-600 dark:text-gray-400 font-medium">{{ $club->club_teams->count() }} участника</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        @if($canEdit)
                        <button wire:click="editClub({{ $club->id }})"
                                class="flex-1 inline-flex items-center justify-center px-4 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white transition-all duration-300 transform hover:scale-105 shadow-lg font-semibold text-sm">
                            <i class="fas fa-edit mr-2"></i>
                            Редактировать
                        </button>
                        @endif

                        <!-- Leave Club Button -->
                        <button wire:click="leaveClub({{ $club->id }})"
                                class="inline-flex items-center justify-center px-4 py-3 rounded-xl bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white transition-all duration-300 transform hover:scale-105 shadow-lg font-semibold text-sm"
                                onclick="return confirm('Вы уверены, что хотите выйти из клуба &quot;{{ $club->short_name_ru }}&quot;? Вы потеряете доступ к управлению этим клубом.')">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Выйти
                        </button>

                        @if($canDelete)
                        <button wire:click="deleteClub({{ $club->id }})"
                                class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white transition-all duration-300 transform hover:scale-105 shadow-lg"
                                onclick="return confirm('Вы уверены, что хотите удалить этот клуб? Удаление возможно только если вы единственный участник команды.')">
                            <i class="fas fa-trash text-lg"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($clubs->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $clubs->links('pagination::livewire-tailwind') }}
        </div>
        @endif
        @else
        <!-- Enhanced Empty State -->
        <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700">
            <div class="flex flex-col items-center">
                <div class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-building text-6xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-2">Клубы не найдены</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md">
                    Попробуйте изменить параметры фильтрации или создайте свой первый клуб
                </p>
                @if($canCreate)
                <button wire:click="$set('showCreateModal', true)"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Создать первый клуб
                </button>
                @endif
            </div>
        </div>
        @endif

        <!-- Enhanced Create Modal -->
        @if($showCreateModal)
        <div wire:ignore.self class="fixed inset-0 overflow-y-auto" style="z-index: 9999;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" wire:click="closeCreateModal" style="z-index: 9998;">
                    <div class="absolute inset-0 bg-gray-900 opacity-75 backdrop-blur-sm"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full border-2 border-gray-200 dark:border-gray-700 relative" style="z-index: 9999;">
                    <form wire:submit.prevent="createClub">
                        <div class="bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-700 dark:to-emerald-700 px-6 py-5">
                            <h3 class="text-2xl font-black text-white flex items-center">
                                <i class="fas fa-plus-circle mr-3 text-3xl"></i>
                                Создание нового клуба
                            </h3>
                            <p class="text-green-100 mt-1 font-medium">Заполните информацию о футбольном клубе</p>
                        </div>

                        <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-4 max-h-[75vh] overflow-y-auto">
                            <div class="grid grid-cols-1 gap-8">
                                <!-- Image Upload Section -->
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-6 rounded-xl border-2 border-dashed border-blue-300 dark:border-blue-700">
                                    <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                        <i class="fas fa-image mr-2 text-blue-600 dark:text-blue-400"></i>
                                        Логотип клуба
                                    </h4>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="create-dropzone-file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-blue-300 border-dashed rounded-xl cursor-pointer bg-white dark:bg-gray-700 hover:bg-blue-50 dark:hover:bg-gray-600 transition-all">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                @if($image)
                                                    <img src="{{ $image->temporaryUrl() }}" class="h-32 w-32 object-cover rounded-lg mb-3 shadow-lg">
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold">Изображение загружено</p>
                                                @else
                                                    <i class="fas fa-cloud-upload-alt text-5xl text-blue-400 mb-3"></i>
                                                    <p class="mb-2 text-sm text-gray-600 dark:text-gray-400 font-semibold">
                                                        <span class="text-blue-600 dark:text-blue-400">Нажмите для загрузки</span> или перетащите файл
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG или JPEG (MAX. 2MB)</p>
                                                @endif
                                            </div>
                                            <input id="create-dropzone-file" type="file" wire:model="image" class="hidden" accept="image/*">
                                        </label>
                                    </div>
                                    @error('image') <span class="text-red-500 dark:text-red-400 text-sm font-semibold mt-2 block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span> @enderror

                                    <div wire:loading wire:target="image" class="mt-3 text-center">
                                        <div class="inline-flex items-center px-4 py-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                            <i class="fas fa-spinner fa-spin mr-2 text-blue-600"></i>
                                            <span class="text-sm text-blue-700 dark:text-blue-300 font-semibold">Загрузка изображения...</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Full Names -->
                                <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl">
                                    <h4 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 pb-3 border-b-2 border-blue-200 dark:border-blue-800 flex items-center">
                                        <i class="fas fa-file-signature mr-2 text-blue-500"></i>
                                        Полное название (на 3 языках)
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На русском*</label>
                                            <input type="text" wire:model="fullNameRu"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                                            @error('fullNameRu') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На казахском*</label>
                                            <input type="text" wire:model="fullNameKk"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                                            @error('fullNameKk') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На английском</label>
                                            <input type="text" wire:model="fullNameEn"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                                            @error('fullNameEn') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Short Names -->
                                <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl">
                                    <h4 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 pb-3 border-b-2 border-green-200 dark:border-green-800 flex items-center">
                                        <i class="fas fa-signature mr-2 text-green-500"></i>
                                        Краткое название (на 3 языках)
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На русском*</label>
                                            <input type="text" wire:model="shortNameRu"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-500/50 focus:border-green-500 transition-all">
                                            @error('shortNameRu') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На казахском*</label>
                                            <input type="text" wire:model="shortNameKk"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-500/50 focus:border-green-500 transition-all">
                                            @error('shortNameKk') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На английском</label>
                                            <input type="text" wire:model="shortNameEn"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-500/50 focus:border-green-500 transition-all">
                                            @error('shortNameEn') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Descriptions -->
                                <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl">
                                    <h4 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 pb-3 border-b-2 border-purple-200 dark:border-purple-800 flex items-center">
                                        <i class="fas fa-align-left mr-2 text-purple-500"></i>
                                        Описание (опционально)
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На русском</label>
                                            <textarea wire:model="descriptionRu" rows="3"
                                                      class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 transition-all resize-none"></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На казахском</label>
                                            <textarea wire:model="descriptionKk" rows="3"
                                                      class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 transition-all resize-none"></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На английском</label>
                                            <textarea wire:model="descriptionEn" rows="3"
                                                      class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 transition-all resize-none"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Legal Information -->
                                <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl">
                                    <h4 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 pb-3 border-b-2 border-orange-200 dark:border-orange-800 flex items-center">
                                        <i class="fas fa-briefcase mr-2 text-orange-500"></i>
                                        Юридическая информация
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">БИН* (12 символов)</label>
                                            <input type="text" wire:model="bin" maxlength="12"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-orange-500/50 focus:border-orange-500 transition-all font-mono">
                                            @error('bin') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Дата основания*</label>
                                            <input type="date" wire:model="foundationDate"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-orange-500/50 focus:border-orange-500 transition-all">
                                            @error('foundationDate') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Юридический адрес*</label>
                                            <input type="text" wire:model="legalAddress"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-orange-500/50 focus:border-orange-500 transition-all">
                                            @error('legalAddress') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Фактический адрес*</label>
                                            <input type="text" wire:model="actualAddress"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-orange-500/50 focus:border-orange-500 transition-all">
                                            @error('actualAddress') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl">
                                    <h4 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 pb-3 border-b-2 border-cyan-200 dark:border-cyan-800 flex items-center">
                                        <i class="fas fa-phone mr-2 text-cyan-500"></i>
                                        Контактная информация
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Веб-сайт</label>
                                            <input type="url" wire:model="website" placeholder="https://example.com"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all">
                                            @error('website') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                            <input type="email" wire:model="email" placeholder="info@club.com"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all">
                                            @error('email') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Телефон</label>
                                            <input type="text" wire:model="phoneNumber" placeholder="+7 (700) 000-00-00"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all">
                                            @error('phoneNumber') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Info -->
                                <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl">
                                    <h4 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 pb-3 border-b-2 border-indigo-200 dark:border-indigo-800 flex items-center">
                                        <i class="fas fa-cog mr-2 text-indigo-500"></i>
                                        Дополнительная информация
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Тип клуба</label>
                                            <select wire:model="typeId"
                                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                                                <option value="">Выберите тип</option>
                                                @foreach($clubTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                                                @endforeach
                                            </select>
                                            @error('typeId') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Родительский клуб</label>
                                            <select wire:model="parentId"
                                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                                                <option value="">Нет родительского клуба</option>
                                                @foreach($parentClubs as $parent)
                                                <option value="{{ $parent->id }}">{{ $parent->short_name_ru }}</option>
                                                @endforeach
                                            </select>
                                            @error('parentId') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-100 dark:bg-gray-700 px-6 py-4 sm:flex sm:flex-row-reverse gap-3">
                            <button type="submit"
                                    class="w-full inline-flex justify-center items-center rounded-xl px-6 py-3.5 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 sm:w-auto text-base">
                                <i class="fas fa-save mr-2"></i>
                                Создать клуб
                            </button>
                            <button type="button"
                                    wire:click="closeCreateModal"
                                    class="mt-3 w-full inline-flex justify-center items-center rounded-xl px-6 py-3.5 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold hover:bg-gray-300 dark:hover:bg-gray-500 transition-all duration-300 sm:mt-0 sm:w-auto text-base">
                                <i class="fas fa-times mr-2"></i>
                                Отмена
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Enhanced Edit Modal (similar structure to create modal) -->
        @if($showEditModal)
        <div wire:ignore.self class="fixed inset-0 overflow-y-auto" style="z-index: 9999;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" wire:click="closeEditModal" style="z-index: 9998;">
                    <div class="absolute inset-0 bg-gray-900 opacity-75 backdrop-blur-sm"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full border-2 border-gray-200 dark:border-gray-700 relative" style="z-index: 9999;">
                    <form wire:submit.prevent="updateClub">
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-700 dark:to-purple-700 px-6 py-5">
                            <h3 class="text-2xl font-black text-white flex items-center">
                                <i class="fas fa-edit mr-3 text-3xl"></i>
                                Редактирование клуба
                            </h3>
                            <p class="text-indigo-100 mt-1 font-medium">Обновите информацию о футбольном клубе</p>
                        </div>

                        <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-4 max-h-[75vh] overflow-y-auto">
                            <div class="grid grid-cols-1 gap-8">
                                <!-- Image Upload Section with Current Image -->
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-6 rounded-xl border-2 border-dashed border-blue-300 dark:border-blue-700">
                                    <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                        <i class="fas fa-image mr-2 text-blue-600 dark:text-blue-400"></i>
                                        Логотип клуба
                                    </h4>

                                    @if($currentImage && !$image)
                                    <div class="mb-4 flex justify-center">
                                        <div class="relative">
                                            <img src="{{ asset('storage/' . $currentImage) }}" class="h-32 w-32 object-cover rounded-lg shadow-lg">
                                            <div class="absolute -top-2 -right-2 bg-green-500 text-white rounded-full p-2">
                                                <i class="fas fa-check text-sm"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-center text-sm text-gray-600 dark:text-gray-400 font-semibold mb-4">Текущий логотип клуба</p>
                                    @endif

                                    <div class="flex items-center justify-center w-full">
                                        <label for="edit-dropzone-file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-blue-300 border-dashed rounded-xl cursor-pointer bg-white dark:bg-gray-700 hover:bg-blue-50 dark:hover:bg-gray-600 transition-all">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                @if($image)
                                                    <img src="{{ $image->temporaryUrl() }}" class="h-32 w-32 object-cover rounded-lg mb-3 shadow-lg">
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold">Новое изображение загружено</p>
                                                @else
                                                    <i class="fas fa-cloud-upload-alt text-5xl text-blue-400 mb-3"></i>
                                                    <p class="mb-2 text-sm text-gray-600 dark:text-gray-400 font-semibold">
                                                        <span class="text-blue-600 dark:text-blue-400">Нажмите для загрузки</span> нового логотипа
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG или JPEG (MAX. 2MB)</p>
                                                @endif
                                            </div>
                                            <input id="edit-dropzone-file" type="file" wire:model="image" class="hidden" accept="image/*">
                                        </label>
                                    </div>
                                    @error('image') <span class="text-red-500 dark:text-red-400 text-sm font-semibold mt-2 block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span> @enderror

                                    <div wire:loading wire:target="image" class="mt-3 text-center">
                                        <div class="inline-flex items-center px-4 py-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                            <i class="fas fa-spinner fa-spin mr-2 text-blue-600"></i>
                                            <span class="text-sm text-blue-700 dark:text-blue-300 font-semibold">Загрузка изображения...</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- All other sections same as create modal but with edit styling -->
                                <!-- Full Names -->
                                <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl">
                                    <h4 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 pb-3 border-b-2 border-blue-200 dark:border-blue-800 flex items-center">
                                        <i class="fas fa-file-signature mr-2 text-blue-500"></i>
                                        Полное название (на 3 языках)
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На русском*</label>
                                            <input type="text" wire:model="fullNameRu"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                                            @error('fullNameRu') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На казахском*</label>
                                            <input type="text" wire:model="fullNameKk"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                                            @error('fullNameKk') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На английском</label>
                                            <input type="text" wire:model="fullNameEn"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                                            @error('fullNameEn') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Short Names -->
                                <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl">
                                    <h4 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 pb-3 border-b-2 border-green-200 dark:border-green-800 flex items-center">
                                        <i class="fas fa-signature mr-2 text-green-500"></i>
                                        Краткое название (на 3 языках)
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На русском*</label>
                                            <input type="text" wire:model="shortNameRu"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-500/50 focus:border-green-500 transition-all">
                                            @error('shortNameRu') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На казахском*</label>
                                            <input type="text" wire:model="shortNameKk"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-500/50 focus:border-green-500 transition-all">
                                            @error('shortNameKk') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На английском</label>
                                            <input type="text" wire:model="shortNameEn"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-green-500/50 focus:border-green-500 transition-all">
                                            @error('shortNameEn') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Descriptions -->
                                <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl">
                                    <h4 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 pb-3 border-b-2 border-purple-200 dark:border-purple-800 flex items-center">
                                        <i class="fas fa-align-left mr-2 text-purple-500"></i>
                                        Описание (опционально)
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На русском</label>
                                            <textarea wire:model="descriptionRu" rows="3"
                                                      class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 transition-all resize-none"></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На казахском</label>
                                            <textarea wire:model="descriptionKk" rows="3"
                                                      class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 transition-all resize-none"></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">На английском</label>
                                            <textarea wire:model="descriptionEn" rows="3"
                                                      class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 transition-all resize-none"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Legal Information -->
                                <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl">
                                    <h4 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 pb-3 border-b-2 border-orange-200 dark:border-orange-800 flex items-center">
                                        <i class="fas fa-briefcase mr-2 text-orange-500"></i>
                                        Юридическая информация
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">БИН* (12 символов)</label>
                                            <input type="text" wire:model="bin" maxlength="12"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-orange-500/50 focus:border-orange-500 transition-all font-mono">
                                            @error('bin') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Дата основания*</label>
                                            <input type="date" wire:model="foundationDate"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-orange-500/50 focus:border-orange-500 transition-all">
                                            @error('foundationDate') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Юридический адрес*</label>
                                            <input type="text" wire:model="legalAddress"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-orange-500/50 focus:border-orange-500 transition-all">
                                            @error('legalAddress') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Фактический адрес*</label>
                                            <input type="text" wire:model="actualAddress"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-orange-500/50 focus:border-orange-500 transition-all">
                                            @error('actualAddress') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl">
                                    <h4 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 pb-3 border-b-2 border-cyan-200 dark:border-cyan-800 flex items-center">
                                        <i class="fas fa-phone mr-2 text-cyan-500"></i>
                                        Контактная информация
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Веб-сайт</label>
                                            <input type="url" wire:model="website" placeholder="https://example.com"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all">
                                            @error('website') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                            <input type="email" wire:model="email" placeholder="info@club.com"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all">
                                            @error('email') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Телефон</label>
                                            <input type="text" wire:model="phoneNumber" placeholder="+7 (700) 000-00-00"
                                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-cyan-500/50 focus:border-cyan-500 transition-all">
                                            @error('phoneNumber') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Info -->
                                <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl">
                                    <h4 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 pb-3 border-b-2 border-indigo-200 dark:border-indigo-800 flex items-center">
                                        <i class="fas fa-cog mr-2 text-indigo-500"></i>
                                        Дополнительная информация
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Тип клуба</label>
                                            <select wire:model="typeId"
                                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                                                <option value="">Выберите тип</option>
                                                @foreach($clubTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->title_ru }}</option>
                                                @endforeach
                                            </select>
                                            @error('typeId') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Родительский клуб</label>
                                            <select wire:model="parentId"
                                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                                                <option value="">Нет родительского клуба</option>
                                                @foreach($parentClubs as $parent)
                                                <option value="{{ $parent->id }}">{{ $parent->short_name_ru }}</option>
                                                @endforeach
                                            </select>
                                            @error('parentId') <span class="text-red-500 dark:text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-100 dark:bg-gray-700 px-6 py-4 sm:flex sm:flex-row-reverse gap-3">
                            <button type="submit"
                                    class="w-full inline-flex justify-center items-center rounded-xl px-6 py-3.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 sm:w-auto text-base">
                                <i class="fas fa-save mr-2"></i>
                                Обновить клуб
                            </button>
                            <button type="button"
                                    wire:click="closeEditModal"
                                    class="mt-3 w-full inline-flex justify-center items-center rounded-xl px-6 py-3.5 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold hover:bg-gray-300 dark:hover:bg-gray-500 transition-all duration-300 sm:mt-0 sm:w-auto text-base">
                                <i class="fas fa-times mr-2"></i>
                                Отмена
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
