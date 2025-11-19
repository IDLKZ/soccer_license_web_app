<div class="min-h-screen bg-gradient-to-br from-gray-50 via-purple-50 to-blue-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('club.licences') }}"
               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg shadow-md border border-gray-200 dark:border-gray-700 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>
                Назад к лицензиям
            </a>
        </div>

        <!-- Licence Header Card -->
        <div class="mb-8 relative overflow-hidden bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 dark:from-purple-700 dark:via-indigo-700 dark:to-blue-700 rounded-2xl shadow-2xl">
            <div class="absolute inset-0 bg-black opacity-5"></div>
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-white rounded-full opacity-5"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-white rounded-full opacity-5"></div>

            <div class="relative p-8">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-4">
                            <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mr-5">
                                <i class="fas fa-certificate text-4xl text-white"></i>
                            </div>
                            <div>
                                @if($licence->is_active)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-500 text-white shadow-lg mb-2">
                                    <i class="fas fa-check-circle mr-1.5"></i>
                                    Активная лицензия
                                </span>
                                @endif
                                <h1 class="text-4xl font-black text-white mb-2">{{ $licence->title_ru ?? 'Лицензия' }}</h1>
                                <p class="text-xl text-purple-100 font-medium">{{ $licence->title_kk ?? '-' }}</p>
                                @if($licence->title_en)
                                <p class="text-lg text-purple-200 mt-1">{{ $licence->title_en ?? '-' }}</p>
                                @endif
                            </div>
                        </div>

                        @if($licence->description_ru)
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 mt-4">
                            <p class="text-white/90 leading-relaxed">{{ $licence->description_ru }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    @if($licence->season)
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-alt text-2xl text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs text-purple-200 font-medium">Сезон</p>
                                <p class="text-white font-bold">{{ $licence->season?->title_ru ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($licence->league)
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-trophy text-2xl text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs text-purple-200 font-medium">Соревнования</p>
                                <p class="text-white font-bold">{{ $licence->league?->title_ru ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-2xl text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs text-purple-200 font-medium">Период действия</p>
                                <p class="text-white font-bold text-sm">
                                    {{ $licence->start_at?->format('d.m.Y') ?? '-' }} - {{ $licence->end_at?->format('d.m.Y') ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Apply Button or Reason -->
                <div class="mt-6">
                    @if($canApply)
                    <button wire:click="openApplicationModal"
                            class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-3 text-xl"></i>
                        <span class="text-lg">Подать заявку на лицензию</span>
                    </button>
                    @elseif($canApplyReason)
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border-2 border-yellow-300 dark:border-yellow-700 rounded-xl p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-2xl text-yellow-600 dark:text-yellow-400"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-bold text-yellow-800 dark:text-yellow-300 mb-1">
                                    Подача заявки недоступна
                                </h3>
                                <p class="text-sm text-yellow-700 dark:text-yellow-400">
                                    {{ $canApplyReason }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
        <div class="mb-6 bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-400 p-4 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 dark:text-green-400 mr-3 text-xl"></i>
                <p class="text-sm text-green-800 dark:text-green-300 font-semibold">
                    {{ session('success') }}
                </p>
            </div>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-6 bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-400 p-4 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 mr-3 text-xl"></i>
                <p class="text-sm text-red-800 dark:text-red-300 font-semibold">
                    {{ session('error') }}
                </p>
            </div>
        </div>
        @endif

        <!-- Deadlines Section (if any) -->
        @if($licence->licence_deadlines->count() > 0)
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-2xl font-black text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                <i class="fas fa-exclamation-triangle mr-3 text-orange-500"></i>
                Дедлайны для ваших клубов
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($licence->licence_deadlines as $deadline)
                <div class="bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-xl p-4 border-2 border-orange-200 dark:border-orange-700">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-gray-100">{{ $deadline->club?->short_name_ru ?? 'Клуб' }}</h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $deadline->club?->short_name_kk ?? '-' }}</p>
                        </div>
                        <i class="fas fa-building text-2xl text-orange-400"></i>
                    </div>
                    <div class="mt-3 pt-3 border-t border-orange-200 dark:border-orange-700">
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Период подачи заявки</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100">
                            {{ $deadline->start_at?->format('d.m.Y') ?? '-' }}
                        </p>
                        <p class="text-xs text-red-600 dark:text-red-400 font-semibold mt-2">
                            <i class="fas fa-calendar-times mr-1"></i>
                            Дедлайн: {{ $deadline->end_at?->format('d.m.Y H:i') ?? '-' }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Requirements Section with Tabs -->
        @if($categories->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/30 dark:to-purple-900/30 px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-black text-gray-900 dark:text-gray-100 flex items-center">
                    <i class="fas fa-file-alt mr-3 text-indigo-600 dark:text-indigo-400"></i>
                    Требования к документам
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Необходимые документы по категориям</p>
            </div>

            <!-- Category Tabs -->
            <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <div class="flex overflow-x-auto px-6 py-2 space-x-2 scrollbar-thin scrollbar-thumb-purple-600 dark:scrollbar-thumb-purple-700 scrollbar-track-purple-100 dark:scrollbar-track-purple-900 hover:scrollbar-thumb-purple-500 dark:hover:scrollbar-thumb-purple-600">
                    @foreach($categories as $category)
                    <button wire:click="switchCategory({{ $category->id }})"
                            class="flex-shrink-0 px-6 py-3 rounded-t-lg font-bold transition-all duration-300 {{ $activeCategory == $category->id ? 'bg-white dark:bg-gray-800 text-purple-600 dark:text-purple-400 border-t-4 border-purple-500' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <span class="flex items-center">
                            <i class="fas fa-folder mr-2"></i>
                            {{ $category->title_ru ?? 'Категория' }}
                            <span class="ml-2 px-2 py-0.5 bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 rounded-full text-xs font-bold">
                                {{ $requirementsByCategory[$category->id]?->count() ?? 0 }}
                            </span>
                        </span>
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                @foreach($categories as $category)
                @if($activeCategory == $category->id)
                <div class="space-y-4">
                    <!-- Category Description -->
                    @if($category->description_ru)
                    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 dark:border-blue-400 p-4 rounded-lg mb-6">
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            <i class="fas fa-info-circle mr-2"></i>
                            {{ $category->description_ru ?? '' }}
                        </p>
                    </div>
                    @endif

                    <!-- Requirements List -->
                    @foreach($requirementsByCategory[$category->id] as $requirement)
                    <div class="group relative bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-5 hover:shadow-lg transition-all duration-300 border-2 border-transparent hover:border-purple-300 dark:hover:border-purple-700">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    @if($requirement->is_required)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 mr-3">
                                        <i class="fas fa-asterisk mr-1 text-xs"></i>
                                        Обязательно
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 mr-3">
                                        <i class="fas fa-minus mr-1"></i>
                                        Опционально
                                    </span>
                                    @endif

                                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                        {{ $requirement->document?->title_ru ?? 'Документ' }}
                                    </h3>
                                </div>

                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    {{ $requirement->document?->title_kk ?? '-' }}
                                </p>

                                @if($requirement->document?->description_ru)
                                <div class="text-sm mb-3 leading-relaxed prose-content">
                                    {!! $requirement->document?->description_ru ?? '' !!}
                                </div>
                                @endif

                                <!-- File Requirements -->
                                <div class="flex flex-wrap gap-3 mt-3">
                                    @if($requirement->allowed_extensions)
                                    @php
                                        $extensions = is_string($requirement->allowed_extensions)
                                            ? json_decode($requirement->allowed_extensions, true)
                                            : $requirement->allowed_extensions;
                                    @endphp
                                    <div class="inline-flex items-center px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                        <i class="fas fa-file-alt mr-2 text-blue-600 dark:text-blue-400"></i>
                                        <span class="text-xs font-semibold text-blue-700 dark:text-blue-300">
                                            Форматы: {{ is_array($extensions) ? implode(', ', $extensions) : $extensions }}
                                        </span>
                                    </div>
                                    @endif

                                    <div class="inline-flex items-center px-3 py-1.5 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                        <i class="fas fa-weight mr-2 text-green-600 dark:text-green-400"></i>
                                        <span class="text-xs font-semibold text-green-700 dark:text-green-300">
                                            Макс. размер: {{ $requirement->max_file_size_mb }} MB
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="ml-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-900/30 dark:to-indigo-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="fas fa-file-invoice text-3xl text-purple-600 dark:text-purple-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @else
        <!-- No Requirements -->
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700">
            <div class="flex flex-col items-center">
                <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-file-alt text-5xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-2">Нет требований к документам</h3>
                <p class="text-gray-500 dark:text-gray-400">Для этой лицензии пока не установлены требования</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Application Submission Modal -->
    @if($showApplicationModal)
    <div class="fixed inset-0 z-50 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-200 dark:scrollbar-track-gray-800 hover:scrollbar-thumb-gray-500 dark:hover:scrollbar-thumb-gray-500" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-80" wire:click="closeApplicationModal"></div>

            <!-- Modal panel -->
            <div class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-xl shadow-xl">
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                        Подача заявки
                    </h3>
                    <button wire:click="closeApplicationModal" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="px-5 py-4">
                    <!-- Club Selection -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Клуб <span class="text-red-500">*</span>
                        </label>

                        @if($availableClubs->count() === 1)
                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-building text-gray-600 dark:text-gray-400 mr-3"></i>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $availableClubs->first()?->short_name_ru ?? 'Клуб' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $availableClubs->first()?->short_name_kk ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="space-y-2">
                            @foreach($availableClubs as $club)
                            <label class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 cursor-pointer hover:border-green-400 dark:hover:border-green-500 has-[:checked]:border-green-500 dark:has-[:checked]:border-green-400 has-[:checked]:bg-green-50 dark:has-[:checked]:bg-green-900/20 transition">
                                <input type="radio" wire:model="selectedClubId" value="{{ $club->id }}" class="w-4 h-4 text-green-600 mr-3">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $club->short_name_ru ?? 'Клуб' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $club->short_name_kk ?? '-' }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @endif

                        @error('selectedClubId')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-2 px-5 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="closeApplicationModal" type="button"
                            class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                        Отмена
                    </button>
                    <button wire:click="submitApplication" type="button"
                            class="px-4 py-2 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
                        <i class="fas fa-paper-plane mr-1.5"></i>
                        Подать заявку
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
