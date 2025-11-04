<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 dark:bg-green-900 dark:border-green-600 dark:text-green-200 px-4 py-3 rounded-lg relative">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 dark:bg-red-900 dark:border-red-600 dark:text-red-200 px-4 py-3 rounded-lg relative">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($application)
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <div class="flex items-center space-x-4 mb-2">
                        <a href="{{ route('club.applications') }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                            Детали заявки #{{ $application->id }}
                        </h1>
                    </div>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Полная информация о заявке на лицензирование
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="{{ $this->getApplicationStatusColor($application->application_status_category->value) }} px-4 py-2 rounded-full text-sm font-medium inline-flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ $application->application_status_category->title_ru }}
                    </span>
                </div>
            </div>

            <!-- Application Info Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- License Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-lg mr-4">
                            <i class="fas fa-certificate text-purple-600 dark:text-purple-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Лицензия</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $licence->title_ru }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Сезон</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $licence->season->title_ru ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Лига</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $licence->league->title_ru ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Период действия</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $licence->start_at->format('d.m.Y') }} - {{ $licence->end_at->format('d.m.Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Club Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 dark:bg-green-900 p-3 rounded-lg mr-4">
                            <i class="fas fa-building text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Клуб</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $club->short_name_ru ?? $club->full_name_ru }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Полное название</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 break-words">{{ $club->full_name_ru }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">БИН</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $club->bin }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Дата основания</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $club->foundation_date->format('d.m.Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- User Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg mr-4">
                            <i class="fas fa-user text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Ответственный</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->role->title_ru ?? 'Пользователь' }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">ФИО</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Email</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->email }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Телефон</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->phone_number ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        @if(!empty($criteriaTabs))
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 container">
            <!-- Tab Headers -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex -mb-px overflow-x-auto scrollbar-thin scrollbar-thumb-indigo-600 dark:scrollbar-thumb-indigo-700 scrollbar-track-indigo-100 dark:scrollbar-track-indigo-900 hover:scrollbar-thumb-indigo-500 dark:hover:scrollbar-thumb-indigo-600">
                    @foreach($criteriaTabs as $tab)
                        <button
                            wire:click="setActiveTab('{{ $tab['category']->id }}')"
                            class="{{ $activeTab === $tab['category']->id ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}
                             flex items-center px-6 py-4 border-b-2 font-medium text-sm transition-colors whitespace-nowrap"
                        >
                            <i class="fas fa-folder mr-2"></i>
                            {{ $tab['title'] }}
                            <span class="ml-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded-full text-xs">
                                {{ count($tab['criteria']) }}
                            </span>
                        </button>
                    @endforeach
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                @if($activeTab)
                    @php
                        $activeCategory = collect($criteriaTabs)->firstWhere('category.id', $activeTab);
                        $criterion = $activeCategory ? collect($activeCategory['criteria'])->first() : null;
                    @endphp

                    @if($activeCategory && $criterion)
                        <!-- Criterion Status and Submit Button -->
                        <div class="mb-6 bg-gray-50 dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                        Статус критерия: {{ $activeCategory['title'] }}
                                    </h3>
                                    <div class="flex items-center space-x-4">
                                        <span class="{{ $this->getCriterionStatusColor($criterion) }} px-3 py-1 rounded-full text-xs font-medium">
                                            @if(!$criterion->is_ready)
                                                <i class="fas fa-clock mr-1"></i> Не готово
                                            @elseif($criterion->is_first_passed === false || $criterion->is_industry_passed === false || $criterion->is_final_passed === false)
                                                <i class="fas fa-exclamation-triangle mr-1"></i> Требует исправлений
                                            @elseif($criterion->is_first_passed === null || $criterion->is_industry_passed === null || $criterion->is_final_passed === null)
                                                <i class="fas fa-spinner mr-1"></i> На проверке
                                            @else
                                                <i class="fas fa-check-circle mr-1"></i> Принято
                                            @endif
                                        </span>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $criterion->application_status->title_ru ?? 'Не определен' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($criterion->application_status && in_array($criterion->application_status->value, ['awaiting-documents', 'first-check-revision']))
                                        @if($this->canSubmitCriterion($criterion, 'first'))
                                            <button
                                                wire:click="submitCriterionForCheck({{ $criterion->id }}, 'first')"
                                                wire:confirm="Отправить критерий на первичную проверку?"
                                                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                                                <i class="fas fa-paper-plane mr-2"></i>
                                                Отправить на первичную проверку
                                            </button>
                                        @endif
                                    @elseif($criterion->application_status && $criterion->application_status->value === 'industry-check-revision')
                                        @if($this->canSubmitCriterion($criterion, 'industry'))
                                            <button
                                                wire:click="submitCriterionForCheck({{ $criterion->id }}, 'industry')"
                                                wire:confirm="Отправить критерий на отраслевую проверку?"
                                                class="bg-orange-600 hover:bg-orange-700 dark:bg-orange-500 dark:hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                                                <i class="fas fa-paper-plane mr-2"></i>
                                                Отправить на отраслевую проверку
                                            </button>
                                        @endif
                                    @elseif($criterion->application_status && $criterion->application_status->value === 'control-check-revision')
                                        @if($this->canSubmitCriterion($criterion, 'control'))
                                            <button
                                                wire:click="submitCriterionForCheck({{ $criterion->id }}, 'control')"
                                                wire:confirm="Отправить критерий на контрольную проверку?"
                                                class="bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                                                <i class="fas fa-paper-plane mr-2"></i>
                                                Отправить на контрольную проверку
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <!-- Comments from Reviews -->
                            @if($criterion->first_comment || $criterion->industry_comment || $criterion->final_comment || $criterion->last_comment)
                                <div class="mt-4 space-y-3">
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        <i class="fas fa-comments mr-2"></i>Комментарии от проверяющих
                                    </h4>

                                    @if($criterion->first_comment)
                                        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-3 rounded">
                                            <div class="flex items-start">
                                                <i class="fas fa-search text-blue-600 dark:text-blue-400 mt-1 mr-2"></i>
                                                <div class="flex-1">
                                                    <p class="text-xs font-semibold text-blue-800 dark:text-blue-300 mb-1">Первичная проверка:</p>
                                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $criterion->first_comment }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($criterion->industry_comment)
                                        <div class="bg-orange-50 dark:bg-orange-900/20 border-l-4 border-orange-500 p-3 rounded">
                                            <div class="flex items-start">
                                                <i class="fas fa-industry text-orange-600 dark:text-orange-400 mt-1 mr-2"></i>
                                                <div class="flex-1">
                                                    <p class="text-xs font-semibold text-orange-800 dark:text-orange-300 mb-1">Отраслевая проверка:</p>
                                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $criterion->industry_comment }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($criterion->final_comment)
                                        <div class="bg-purple-50 dark:bg-purple-900/20 border-l-4 border-purple-500 p-3 rounded">
                                            <div class="flex items-start">
                                                <i class="fas fa-clipboard-check text-purple-600 dark:text-purple-400 mt-1 mr-2"></i>
                                                <div class="flex-1">
                                                    <p class="text-xs font-semibold text-purple-800 dark:text-purple-300 mb-1">Контрольная проверка:</p>
                                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $criterion->final_comment }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($criterion->last_comment)
                                        <div class="bg-indigo-50 dark:bg-indigo-900/20 border-l-4 border-indigo-500 p-3 rounded">
                                            <div class="flex items-start">
                                                <i class="fas fa-gavel text-indigo-600 dark:text-indigo-400 mt-1 mr-2"></i>
                                                <div class="flex-1">
                                                    <p class="text-xs font-semibold text-indigo-800 dark:text-indigo-300 mb-1">Финальное решение:</p>
                                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $criterion->last_comment }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Documents by Requirements -->
                        @if(!empty($licenceRequirementsByCategory))
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                    Требования к документам
                                </h3>

                                @foreach($licenceRequirementsByCategory as $documentId => $data)
                                    @php
                                        $document = $data['document'];
                                        $requirements = $data['requirements'];
                                        $requirement = collect($requirements)->first();
                                        $uploadedDocs = $this->getUploadedDocumentsForRequirement($documentId);
                                    @endphp
                                    @if($document && $requirement)
                                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                                        <!-- Document Header -->
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                                                    <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $document->title_ru }}
                                                    </h4>
                                                    @if($requirement['is_required'])
                                                        <span class="ml-2 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-1 rounded text-xs font-medium">
                                                            Обязательно
                                                        </span>
                                                    @else
                                                        <span class="ml-2 bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 px-2 py-1 rounded text-xs font-medium">
                                                            Необязательно
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($document->description_ru)
                                                    <div class="text-sm ml-6 prose-content">
                                                        {!! $document->description_ru !!}
                                                    </div>
                                                @endif
                                                <div class="flex items-center space-x-4 ml-6 mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                    @if(!empty($requirement['allowed_extensions']))
                                                        <span>
                                                            <i class="fas fa-file-code mr-1"></i>
                                                            Форматы: {{ is_array($requirement['allowed_extensions']) ? implode(', ', $requirement['allowed_extensions']) : $requirement['allowed_extensions'] }}
                                                        </span>
                                                    @endif
                                                    @if($requirement['max_file_size_mb'])
                                                        <span>
                                                            <i class="fas fa-weight mr-1"></i>
                                                            Макс. размер: {{ $requirement['max_file_size_mb'] }} МБ
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($this->canUploadDocument($criterion, $documentId))
                                                <button
                                                    wire:click="openUploadModal({{ $criterion->id }}, {{ $requirement['id'] }})"
                                                    class="bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                                                    <i class="fas fa-upload mr-2"></i>
                                                    Загрузить
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Uploaded Documents List -->
                                        <div class="mt-4 space-y-2">
                                            @if(!empty($uploadedDocs))
                                                @foreach($uploadedDocs as $appDoc)
                                                    @php
                                                        $doc = is_array($appDoc) ? (object)$appDoc : $appDoc;
                                                    @endphp
                                                    <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-400 dark:hover:border-blue-600 transition-colors cursor-pointer" wire:click="openDocumentInfoModal({{ $doc->id }})">
                                                        <div class="flex items-center flex-1">
                                                            <i class="fas fa-file text-gray-400 mr-3"></i>
                                                            <div class="flex-1">
                                                                <div class="flex items-center">
                                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                        {{ $doc->title ?? 'Документ' }}
                                                                    </span>
                                                                    @if($doc->is_first_passed === false)
                                                                        <span class="ml-2 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-1 rounded text-xs">
                                                                            <i class="fas fa-times mr-1"></i>Не прошел первичную
                                                                        </span>
                                                                    @elseif($doc->is_industry_passed === false)
                                                                        <span class="ml-2 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-1 rounded text-xs">
                                                                            <i class="fas fa-times mr-1"></i>Не прошел отраслевую
                                                                        </span>
                                                                    @elseif($doc->is_final_passed === false)
                                                                        <span class="ml-2 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-1 rounded text-xs">
                                                                            <i class="fas fa-times mr-1"></i>Не прошел контрольную
                                                                        </span>
                                                                    @elseif($doc->is_first_passed === null && $doc->is_industry_passed === null && $doc->is_final_passed === null)
                                                                        <span class="ml-2 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-2 py-1 rounded text-xs">
                                                                            <i class="fas fa-clock mr-1"></i>Ожидает проверки
                                                                        </span>
                                                                    @elseif($doc->is_first_passed === true && $doc->is_industry_passed === true && $doc->is_final_passed === true)
                                                                        <span class="ml-2 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-2 py-1 rounded text-xs">
                                                                            <i class="fas fa-check mr-1"></i>Принято
                                                                        </span>
                                                                    @endif

                                                                    @php
                                                                        // Check if document needs reupload for partially-approved status
                                                                        $statusValue = $criterion->application_status->value ?? null;
                                                                        $needsReupload = false;
                                                                        if ($statusValue === 'partially-approved') {
                                                                            $reuploadDocIds = $criterion->can_reupload_after_endings_doc_ids ?? [];
                                                                            if (is_string($reuploadDocIds)) {
                                                                                $reuploadDocIds = json_decode($reuploadDocIds, true) ?? [];
                                                                            }
                                                                            $needsReupload = in_array($doc->document_id ?? null, $reuploadDocIds);
                                                                        }
                                                                    @endphp

                                                                    @if($needsReupload)
                                                                        <span class="ml-2 bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200 px-2 py-1 rounded text-xs">
                                                                            <i class="fas fa-redo mr-1"></i>Требуется перезагрузка
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                @if($doc->info)
                                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $doc->info }}</p>
                                                                @endif
                                                                <div class="flex items-center space-x-3 mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                                    <span>
                                                                        <i class="fas fa-user mr-1"></i>{{ $doc->uploaded_by ?? 'Неизвестно' }}
                                                                    </span>
                                                                    <span>
                                                                        <i class="fas fa-clock mr-1"></i>{{ isset($doc->created_at) ? (is_string($doc->created_at) ? $doc->created_at : $doc->created_at->format('d.m.Y H:i')) : '-' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center space-x-2 ml-4" onclick="event.stopPropagation()">
                                                            @if($doc->file_url)
                                                                <a href="{{ Storage::url($doc->file_url) }}" target="_blank" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 p-2">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            @endif
                                                            @if($this->canEditOrDeleteDocument($criterion, $doc))
                                                                <button
                                                                    wire:click="openEditModal({{ $doc->id }})"
                                                                    class="text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-300 p-2"
                                                                    title="Редактировать">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button
                                                                    wire:click="deleteDocument({{ $doc->id }})"
                                                                    wire:confirm="Вы уверены, что хотите удалить этот документ?"
                                                                    class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 p-2"
                                                                    title="Удалить">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-center py-6 text-gray-500 dark:text-gray-400 text-sm">
                                                    <i class="fas fa-inbox text-3xl mb-2"></i>
                                                    <p>Документы еще не загружены</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="bg-gray-100 dark:bg-gray-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-folder-open text-gray-400 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    Нет требований
                                </h3>
                                <p class="text-gray-500 dark:text-gray-400">
                                    Для этой категории не определены требования к документам.
                                </p>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </div>

        <!-- Upload Modal -->
        @if($showUploadModal && $selectedRequirement && $selectedCriterion)
            <div class="fixed inset-0  bg-opacity-50 flex items-center justify-center z-50" wire:click.self="closeUploadModal">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-lg w-full mx-4">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Загрузка документа
                            </h3>
                            <button wire:click="closeUploadModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <form wire:submit.prevent="uploadDocument">
                            <div class="space-y-4">
                                <!-- Document Info -->
                                <div class="bg-blue-50 dark:bg-blue-900 p-3 rounded-lg">
                                    <p class="text-sm text-blue-800 dark:text-blue-200">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        {{ $selectedRequirement['document']['title_ru'] ?? 'Документ' }}
                                    </p>
                                    @if(!empty($selectedRequirement['allowed_extensions']))
                                        <p class="text-xs text-blue-600 dark:text-blue-300 mt-1 wrap-break-word">
                                            Форматы: {{ is_array($selectedRequirement['allowed_extensions']) ? implode(', ', $selectedRequirement['allowed_extensions']) : $selectedRequirement['allowed_extensions'] }}
                                        </p>
                                    @endif
                                    @if(isset($selectedRequirement['max_file_size_mb']))
                                        <p class="text-xs text-blue-600 dark:text-blue-300">
                                            Макс. размер: {{ $selectedRequirement['max_file_size_mb'] }} МБ
                                        </p>
                                    @endif
                                </div>

                                <!-- File Upload -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                        Файл <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="file"
                                        wire:model="uploadFile"
                                        class="block w-full text-sm text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                    @error('uploadFile')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror

                                    <!-- Upload Progress -->
                                    <div wire:loading wire:target="uploadFile" class="mt-2">
                                        <div class="flex items-center text-sm text-blue-600 dark:text-blue-400">
                                            <i class="fas fa-spinner fa-spin mr-2"></i>
                                            Загрузка файла...
                                        </div>
                                    </div>

                                    @if($uploadFile)
                                        <p class="mt-1 text-xs text-green-600 dark:text-green-400" wire:loading.remove wire:target="uploadFile">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Файл выбран
                                        </p>
                                    @endif
                                </div>

                                <!-- Title -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                        Название документа <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="uploadTitle"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                        placeholder="Введите название документа"
                                    >
                                    @error('uploadTitle')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Info -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                        Дополнительная информация
                                    </label>
                                    <textarea
                                        wire:model="uploadInfo"
                                        rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                        placeholder="Введите дополнительную информацию (необязательно)"
                                    ></textarea>
                                    @error('uploadInfo')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 mt-6">
                                <button
                                    type="button"
                                    wire:click="closeUploadModal"
                                    class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">
                                    Отмена
                                </button>
                                <button
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="uploadFile,uploadDocument"
                                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium rounded-lg transition-colors inline-flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-upload mr-2"></i>
                                    <span wire:loading.remove wire:target="uploadFile,uploadDocument">Загрузить</span>
                                    <span wire:loading wire:target="uploadFile,uploadDocument">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>
                                        Загрузка...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Edit Modal -->
        @if($showEditModal && $editingDocument)
            <div class="fixed inset-0  bg-opacity-50 flex items-center justify-center z-50" wire:click.self="closeEditModal">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-lg w-full mx-4">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Редактирование документа
                            </h3>
                            <button wire:click="closeEditModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <form wire:submit.prevent="updateDocument">
                            <div class="space-y-4">
                                <!-- Current File Info -->
                                <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-lg">
                                    <p class="text-sm text-gray-800 dark:text-gray-200">
                                        <i class="fas fa-file mr-2"></i>
                                        Текущий файл: {{ basename($editingDocument->file_url) }}
                                    </p>
                                </div>

                                <!-- Replace File (Optional) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                        Заменить файл (необязательно)
                                    </label>
                                    <input
                                        type="file"
                                        wire:model="uploadFile"
                                        class="block w-full text-sm text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                    @if($uploadFile)
                                        <p class="mt-1 text-xs text-green-600 dark:text-green-400">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Новый файл будет загружен
                                        </p>
                                    @endif
                                </div>

                                <!-- Title -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                        Название документа <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="uploadTitle"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    >
                                    @error('uploadTitle')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Info -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                        Дополнительная информация
                                    </label>
                                    <textarea
                                        wire:model="uploadInfo"
                                        rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    ></textarea>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 mt-6">
                                <button
                                    type="button"
                                    wire:click="closeEditModal"
                                    class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">
                                    Отмена
                                </button>
                                <button
                                    type="submit"
                                    class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 dark:bg-yellow-500 dark:hover:bg-yellow-600 text-white font-medium rounded-lg transition-colors inline-flex items-center">
                                    <i class="fas fa-save mr-2"></i>
                                    Сохранить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @else
        <div class="text-center py-12">
            <div class="bg-gray-100 dark:bg-gray-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                Нет доступных критериев
            </h3>
            <p class="text-gray-500 dark:text-gray-400">
                У вас нет прав для просмотра критериев этой заявки.
            </p>
        </div>
        @endif
    @else
    <div class="text-center py-12">
        <div class="bg-gray-100 dark:bg-gray-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-search text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
            Заявка не найдена
        </h3>
        <p class="text-gray-500 dark:text-gray-400">
            Запрошенная заявка не существует или у вас нет прав для ее просмотра.
        </p>
        <div class="mt-6">
            <a href="{{ route('club.applications') }}" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Вернуться к заявкам
            </a>
        </div>
    </div>
    @endif

    <!-- Document Info Modal -->
    @if($showDocumentInfoModal && $viewingDocument)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg mr-4">
                            <i class="fas fa-file-alt text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                Информация о документе
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Подробная информация и статусы проверок
                            </p>
                        </div>
                    </div>
                    <button wire:click="closeDocumentInfoModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)] scrollbar-thin scrollbar-thumb-blue-600 dark:scrollbar-thumb-blue-700 scrollbar-track-blue-100 dark:scrollbar-track-blue-900 hover:scrollbar-thumb-blue-500 dark:hover:scrollbar-thumb-blue-600">
                    <!-- Document Details -->
                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                Основная информация
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400">Название документа</label>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $viewingDocument->title }}</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400">Тип документа</label>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $viewingDocument->document->title_ru ?? '-' }}</p>
                                </div>
                                @if($viewingDocument->info)
                                    <div class="md:col-span-2">
                                        <label class="text-xs text-gray-500 dark:text-gray-400">Дополнительная информация</label>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $viewingDocument->info }}</p>
                                    </div>
                                @endif
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400">Загружено пользователем</label>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $viewingDocument->user->name ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400">Дата загрузки</label>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $viewingDocument->created_at->format('d.m.Y H:i') }}</p>
                                </div>
                                @if($viewingDocument->file_url)
                                    <div class="md:col-span-2">
                                        <label class="text-xs text-gray-500 dark:text-gray-400">Файл</label>
                                        <div class="flex items-center mt-1">
                                            <i class="fas fa-paperclip text-gray-400 mr-2"></i>
                                            <a href="{{ Storage::url($viewingDocument->file_url) }}" target="_blank"
                                               class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                                Скачать документ
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Application Information -->
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                <i class="fas fa-building text-green-500 mr-2"></i>
                                Информация о заявке
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400">Клуб</label>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $viewingDocument->application->club->short_name_ru ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500 dark:text-gray-400">Лицензия</label>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $viewingDocument->application->licence->title_ru ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Review Statuses -->
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                                <i class="fas fa-clipboard-check text-purple-500 mr-2"></i>
                                Статусы проверок
                            </h4>
                            <div class="space-y-4">
                                <!-- First Check -->
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mr-3">
                                        @if($viewingDocument->is_first_passed === null)
                                            <div class="bg-blue-100 dark:bg-blue-900/50 p-2 rounded-full">
                                                <i class="fas fa-clock text-blue-600 dark:text-blue-400"></i>
                                            </div>
                                        @elseif($viewingDocument->is_first_passed === true)
                                            <div class="bg-green-100 dark:bg-green-900/50 p-2 rounded-full">
                                                <i class="fas fa-check text-green-600 dark:text-green-400"></i>
                                            </div>
                                        @else
                                            <div class="bg-red-100 dark:bg-red-900/50 p-2 rounded-full">
                                                <i class="fas fa-times text-red-600 dark:text-red-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center mb-1">
                                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Первичная проверка</span>
                                            @if($viewingDocument->is_first_passed === null)
                                                <span class="ml-2 bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 px-2 py-1 rounded text-xs">Ожидает</span>
                                            @elseif($viewingDocument->is_first_passed === true)
                                                <span class="ml-2 bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 px-2 py-1 rounded text-xs">Одобрено</span>
                                            @else
                                                <span class="ml-2 bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 px-2 py-1 rounded text-xs">Отклонено</span>
                                            @endif
                                        </div>
                                        @if($viewingDocument->first_comment)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 p-2 rounded mt-1">
                                                {{ $viewingDocument->first_comment }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Industry Check -->
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mr-3">
                                        @if($viewingDocument->is_industry_passed === null)
                                            <div class="bg-blue-100 dark:bg-blue-900/50 p-2 rounded-full">
                                                <i class="fas fa-clock text-blue-600 dark:text-blue-400"></i>
                                            </div>
                                        @elseif($viewingDocument->is_industry_passed === true)
                                            <div class="bg-green-100 dark:bg-green-900/50 p-2 rounded-full">
                                                <i class="fas fa-check text-green-600 dark:text-green-400"></i>
                                            </div>
                                        @else
                                            <div class="bg-red-100 dark:bg-red-900/50 p-2 rounded-full">
                                                <i class="fas fa-times text-red-600 dark:text-red-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center mb-1">
                                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Отраслевая проверка</span>
                                            @if($viewingDocument->is_industry_passed === null)
                                                <span class="ml-2 bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 px-2 py-1 rounded text-xs">Ожидает</span>
                                            @elseif($viewingDocument->is_industry_passed === true)
                                                <span class="ml-2 bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 px-2 py-1 rounded text-xs">Одобрено</span>
                                            @else
                                                <span class="ml-2 bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 px-2 py-1 rounded text-xs">Отклонено</span>
                                            @endif
                                        </div>
                                        @if($viewingDocument->industry_comment)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 p-2 rounded mt-1">
                                                {{ $viewingDocument->industry_comment }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Final Check -->
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mr-3">
                                        @if($viewingDocument->is_final_passed === null)
                                            <div class="bg-blue-100 dark:bg-blue-900/50 p-2 rounded-full">
                                                <i class="fas fa-clock text-blue-600 dark:text-blue-400"></i>
                                            </div>
                                        @elseif($viewingDocument->is_final_passed === true)
                                            <div class="bg-green-100 dark:bg-green-900/50 p-2 rounded-full">
                                                <i class="fas fa-check text-green-600 dark:text-green-400"></i>
                                            </div>
                                        @else
                                            <div class="bg-red-100 dark:bg-red-900/50 p-2 rounded-full">
                                                <i class="fas fa-times text-red-600 dark:text-red-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center mb-1">
                                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Контрольная проверка</span>
                                            @if($viewingDocument->is_final_passed === null)
                                                <span class="ml-2 bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 px-2 py-1 rounded text-xs">Ожидает</span>
                                            @elseif($viewingDocument->is_final_passed === true)
                                                <span class="ml-2 bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 px-2 py-1 rounded text-xs">Одобрено</span>
                                            @else
                                                <span class="ml-2 bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 px-2 py-1 rounded text-xs">Отклонено</span>
                                            @endif
                                        </div>
                                        @if($viewingDocument->final_comment)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 p-2 rounded mt-1">
                                                {{ $viewingDocument->final_comment }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="closeDocumentInfoModal"
                            class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Закрыть
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
