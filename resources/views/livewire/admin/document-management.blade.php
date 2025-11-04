<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Управление документами</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Создание, редактирование и управление документами системы</p>
        </div>
        @if($canCreate)
        <button wire:click="openCreateModal"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors duration-150">
            <i class="fas fa-plus mr-2"></i>
            Создать документ
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
                       placeholder="Название документа..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-folder mr-1 text-gray-400 dark:text-gray-500"></i>
                    Категория
                </label>
                <select wire:model.live="filterCategory"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                    <option value="">Все категории</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->title_ru }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-layer-group mr-1 text-gray-400 dark:text-gray-500"></i>
                    Уровень
                </label>
                <select wire:model.live="filterLevel"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                    <option value="">Все уровни</option>
                    @for($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}">Уровень {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    @if($documents->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg dark:shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-file-alt mr-1 text-gray-400 dark:text-gray-500"></i>
                                Документ
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-folder mr-1 text-gray-400 dark:text-gray-500"></i>
                                Категория
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-layer-group mr-1 text-gray-400 dark:text-gray-500"></i>
                                Уровень
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-file-download mr-1 text-gray-400 dark:text-gray-500"></i>
                                Пример
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
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($documents as $document)
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/30 transition-all duration-150 border-l-4 border-transparent hover:border-blue-400 dark:hover:border-blue-500">
                        <td class="px-4 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-md">
                                    <i class="fas fa-file-alt text-white text-lg"></i>
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">
                                        {{ $document->title_ru }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        {{ $document->title_kk }}
                                    </div>
                                    @if($document->description_ru)
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                                        {!! Str::limit(strip_tags($document->description_ru), 100) !!}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            @if($document->category_document)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-700">
                                <i class="fas fa-folder mr-1 text-purple-600 dark:text-purple-400"></i>
                                {{ $document->category_document->title_ru }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400 dark:text-gray-500 italic">Без категории</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-700">
                                <i class="fas fa-layer-group mr-1 text-blue-600 dark:text-blue-400"></i>
                                Уровень {{ $document->level }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            @if($document->example_file_url)
                            <a href="{{ asset('storage/' . $document->example_file_url) }}" target="_blank"
                               class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-700 hover:from-green-200 hover:to-emerald-200 dark:hover:from-green-900/50 dark:hover:to-emerald-900/50 transition-all">
                                <i class="fas fa-download mr-1 text-green-600 dark:text-green-400"></i>
                                Скачать
                            </a>
                            @else
                            <span class="text-xs text-gray-400 dark:text-gray-500 italic">Нет файла</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($canEdit)
                                <button wire:click="editDocument({{ $document->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/30 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-150"
                                        title="Редактировать">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                @endif
                                @if($canDelete)
                                <button wire:click="deleteDocument({{ $document->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150"
                                        title="Удалить"
                                        onclick="return confirm('Вы уверены, что хотите удалить этот документ?')">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Пагинация -->
    @if($documents->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $documents->links('pagination::livewire-tailwind') }}
    </div>
    @endif
    @else
    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="flex flex-col items-center">
            <i class="fas fa-file-alt text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Документы не найдены</p>
            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Попробуйте изменить параметры фильтрации</p>
        </div>
    </div>
    @endif

    <!-- Create Modal -->
    @if($showCreateModal)
    <div wire:ignore.self class="fixed inset-0 overflow-y-auto" style="z-index: 9999;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="closeCreateModal" style="z-index: 9998;">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full border border-gray-200 dark:border-gray-700 relative" style="z-index: 9999;">
                <form wire:submit.prevent="createDocument">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Создание документа</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Titles -->
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Название (RU)*
                                    </label>
                                    <input type="text"
                                           wire:model="titleRu"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Атауы (KK)*
                                    </label>
                                    <input type="text"
                                           wire:model="titleKk"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Title (EN)
                                    </label>
                                    <input type="text"
                                           wire:model="titleEn"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Descriptions -->
                            <div class="grid grid-cols-1 gap-4">
                                <div wire:ignore>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (RU)
                                    </label>
                                    <textarea id="create-description-ru" class="summernote-create"></textarea>
                                    @error('descriptionRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div wire:ignore>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Сипаттама (KK)
                                    </label>
                                    <textarea id="create-description-kk" class="summernote-create"></textarea>
                                    @error('descriptionKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div wire:ignore>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Description (EN)
                                    </label>
                                    <textarea id="create-description-en" class="summernote-create"></textarea>
                                    @error('descriptionEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Category and Level -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Категория
                                    </label>
                                    <select wire:model="categoryId"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="">Без категории</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->title_ru }}</option>
                                        @endforeach
                                    </select>
                                    @error('categoryId') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Уровень*
                                    </label>
                                    <select wire:model="level"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">Уровень {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('level') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- File Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Пример файла (макс. 10MB)
                                </label>
                                <input type="file"
                                       wire:model="exampleFile"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                @error('exampleFile') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror

                                @if($exampleFile)
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-file mr-1"></i>
                                    Выбран файл: {{ $exampleFile->getClientOriginalName() }}
                                </div>
                                @endif

                                <div wire:loading wire:target="exampleFile" class="mt-2 text-sm text-blue-600 dark:text-blue-400">
                                    <i class="fas fa-spinner fa-spin mr-1"></i>
                                    Загрузка файла...
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 dark:bg-blue-500 text-base font-medium text-white hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Создать
                        </button>
                        <button type="button"
                                wire:click="closeCreateModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
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
    <div wire:ignore.self class="fixed inset-0 overflow-y-auto" style="z-index: 9999;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" wire:click="closeEditModal" style="z-index: 9998;">
                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full border border-gray-200 dark:border-gray-700 relative" style="z-index: 9999;">
                <form wire:submit.prevent="updateDocument">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[90vh] overflow-y-auto">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Редактирование документа</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Titles -->
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Название (RU)*
                                    </label>
                                    <input type="text"
                                           wire:model="titleRu"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Атауы (KK)*
                                    </label>
                                    <input type="text"
                                           wire:model="titleKk"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Title (EN)
                                    </label>
                                    <input type="text"
                                           wire:model="titleEn"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                    @error('titleEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Descriptions -->
                            <div class="grid grid-cols-1 gap-4">
                                <div wire:ignore>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Описание (RU)
                                    </label>
                                    <textarea id="edit-description-ru" class="summernote-edit"></textarea>
                                    @error('descriptionRu') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div wire:ignore>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Сипаттама (KK)
                                    </label>
                                    <textarea id="edit-description-kk" class="summernote-edit"></textarea>
                                    @error('descriptionKk') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div wire:ignore>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Description (EN)
                                    </label>
                                    <textarea id="edit-description-en" class="summernote-edit"></textarea>
                                    @error('descriptionEn') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Category and Level -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Категория
                                    </label>
                                    <select wire:model="categoryId"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        <option value="">Без категории</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->title_ru }}</option>
                                        @endforeach
                                    </select>
                                    @error('categoryId') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Уровень*
                                    </label>
                                    <select wire:model="level"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                        @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">Уровень {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('level') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Current File -->
                            @if($currentExampleFile)
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-file text-blue-500 mr-2"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Текущий файл</p>
                                            <a href="{{ asset('storage/' . $currentExampleFile) }}" target="_blank"
                                               class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                                Скачать файл
                                            </a>
                                        </div>
                                    </div>
                                    <button type="button"
                                            wire:click="removeExampleFile"
                                            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                                            onclick="return confirm('Удалить текущий файл?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endif

                            <!-- File Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ $currentExampleFile ? 'Заменить файл (макс. 10MB)' : 'Загрузить файл (макс. 10MB)' }}
                                </label>
                                <input type="file"
                                       wire:model="exampleFile"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                                @error('exampleFile') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror

                                @if($exampleFile)
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fas fa-file mr-1"></i>
                                    Выбран файл: {{ $exampleFile->getClientOriginalName() }}
                                </div>
                                @endif

                                <div wire:loading wire:target="exampleFile" class="mt-2 text-sm text-blue-600 dark:text-blue-400">
                                    <i class="fas fa-spinner fa-spin mr-1"></i>
                                    Загрузка файла...
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 dark:bg-blue-500 text-base font-medium text-white hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Обновить
                        </button>
                        <button type="button"
                                wire:click="closeEditModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Отмена
                        </button>
    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Summernote Integration Script -->
    <script>
        document.addEventListener('livewire:init', () => {
            // Summernote configuration
            const summernoteConfig = {
                height: 150,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            };

            // Initialize Create Modal Summernote
            Livewire.on('openCreateModal', () => {
                setTimeout(() => {
                    $('.summernote-create').each(function() {
                        const id = $(this).attr('id');
                        if (id) {
                            $(this).summernote(summernoteConfig);

                            // Sync with Livewire on change
                            $(this).on('summernote.change', function(we, contents, $editable) {
                                const wireModel = id.replace('create-', '');
                                const camelCase = wireModel.replace(/-([a-z])/g, function (g) { return g[1].toUpperCase(); });
                                @this.set(camelCase, contents);
                            });
                        }
                    });
                }, 300);
            });

            // Initialize Edit Modal Summernote
            Livewire.on('openEditModal', (data) => {
                setTimeout(() => {
                    $('.summernote-edit').each(function() {
                        const id = $(this).attr('id');
                        if (id) {
                            const wireModel = id.replace('edit-', '');
                            const camelCase = wireModel.replace(/-([a-z])/g, function (g) { return g[1].toUpperCase(); });

                            // Get the value from Livewire component
                            const value = @this.get(camelCase) || '';

                            // Initialize with value
                            $(this).summernote(summernoteConfig);
                            $(this).summernote('code', value);

                            // Sync with Livewire on change
                            $(this).on('summernote.change', function(we, contents, $editable) {
                                @this.set(camelCase, contents);
                            });
                        }
                    });
                }, 300);
            });

            // Destroy Summernote when modals close
            Livewire.on('closeCreateModal', () => {
                $('.summernote-create').each(function() {
                    if ($(this).summernote('instance')) {
                        $(this).summernote('destroy');
                    }
                });
            });

            Livewire.on('closeEditModal', () => {
                $('.summernote-edit').each(function() {
                    if ($(this).summernote('instance')) {
                        $(this).summernote('destroy');
                    }
                });
            });

            // Watch for showCreateModal changes
            Livewire.hook('morph.updated', ({ component, cleanup }) => {
                if (@this.showCreateModal) {
                    setTimeout(() => {
                        $('.summernote-create').each(function() {
                            if (!$(this).summernote('instance')) {
                                const id = $(this).attr('id');
                                $(this).summernote(summernoteConfig);

                                $(this).on('summernote.change', function(we, contents, $editable) {
                                    const wireModel = id.replace('create-', '');
                                    const camelCase = wireModel.replace(/-([a-z])/g, function (g) { return g[1].toUpperCase(); });
                                    @this.set(camelCase, contents);
                                });
                            }
                        });
                    }, 300);
                }

                if (@this.showEditModal) {
                    setTimeout(() => {
                        $('.summernote-edit').each(function() {
                            if (!$(this).summernote('instance')) {
                                const id = $(this).attr('id');
                                const wireModel = id.replace('edit-', '');
                                const camelCase = wireModel.replace(/-([a-z])/g, function (g) { return g[1].toUpperCase(); });

                                const value = @this.get(camelCase) || '';

                                $(this).summernote(summernoteConfig);
                                $(this).summernote('code', value);

                                $(this).on('summernote.change', function(we, contents, $editable) {
                                    @this.set(camelCase, contents);
                                });
                            }
                        });
                    }, 300);
                }
            });
        });
    </script>
</div>
