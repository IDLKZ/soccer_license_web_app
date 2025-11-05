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
                        <a href="{{ route('department.applications') }}" class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                            Детали заявки #{{ $application->id }}
                        </h1>
                    </div>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Проверка и утверждение документов
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="{{ $this->getApplicationStatusColor($application->application_status_category->value) }} px-4 py-2 rounded-full text-sm font-medium inline-flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ $application->application_status_category->title_ru }}
                    </span>

                    @if($this->canRejectApplication())
                        <button
                            wire:click="openRejectApplicationModal"
                            class="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                            <i class="fas fa-times-circle mr-2"></i>
                            Отказать заявку
                        </button>
                    @endif
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

        <!-- Final Decision Progress (2.4.1) -->
        @php
            $finalStats = $this->getFinalDecisionStats();
            $showFinalProgress = $finalStats['awaiting'] > 0;
        @endphp

        @if($showFinalProgress)
        <div class="mb-6 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900 dark:to-purple-900 rounded-xl p-6 border border-indigo-200 dark:border-indigo-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                        <i class="fas fa-clock mr-2 text-indigo-600 dark:text-indigo-400"></i>
                        Прогресс финального решения
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Критериев на этапе финального решения: <span class="font-bold">{{ $finalStats['awaiting'] }}/{{ $finalStats['total'] }}</span>
                    </p>
                </div>
                @if($this->canMakeFinalDecision())
                    <button
                        wire:click="openFinalDecisionModal"
                        class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium py-3 px-6 rounded-lg transition-colors inline-flex items-center">
                        <i class="fas fa-gavel mr-2"></i>
                        Принять финальное решение
                    </button>
                @endif
            </div>
        </div>
        @endif

        <!-- Application Status Change Buttons (2.4.3) -->
        @php
            $allCriteria = \App\Models\ApplicationCriterion::with('application_status')
                ->where('application_id', $application->id)
                ->get();
            $allInFinalStatus = $allCriteria->every(function($c) {
                return in_array($c->application_status->value ?? '', [
                    'fully-approved',
                    'partially-approved',
                    'revoked'
                ]);
            });
        @endphp

        @if($allInFinalStatus && !in_array($application->application_status_category->value, ['approved', 'revoked']))
        <div class="mb-6 bg-green-50 dark:bg-green-900 rounded-xl p-6 border border-green-200 dark:border-green-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                <i class="fas fa-check-double mr-2 text-green-600 dark:text-green-400"></i>
                Все критерии получили финальное решение
            </h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Теперь вы можете изменить общий статус заявки:</p>

            @php
                $allCriteria = \App\Models\ApplicationCriterion::with('application_status')
                    ->where('application_id', $application->id)
                    ->get();

                $statusSummary = [];
                foreach ($allCriteria as $criterion) {
                    if ($criterion->application_status) {
                        $status = $criterion->application_status->value;
                        if (!isset($statusSummary[$status])) {
                            $statusSummary[$status] = 0;
                        }
                        $statusSummary[$status]++;
                    }
                }

                $summaryText = 'Статусы критериев: ';
                $parts = [];
                if (isset($statusSummary['fully-approved'])) {
                    $parts[] = "полностью одобрено: {$statusSummary['fully-approved']}";
                }
                if (isset($statusSummary['partially-approved'])) {
                    $parts[] = "частично одобрено: {$statusSummary['partially-approved']}";
                }
                if (isset($statusSummary['revoked'])) {
                    $parts[] = "отозвано: {$statusSummary['revoked']}";
                }
                $summaryText .= implode(', ', $parts) . '. Выбор решения за ответственным сотрудником.';
            @endphp

            <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900 rounded-lg border border-blue-200 dark:border-blue-700">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    <i class="fas fa-info-circle mr-1"></i>
                    {{ $summaryText }}
                </p>
            </div>

            <!-- Decision Selection -->
            <div class="mb-4 space-y-3">
                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer {{ $applicationFinalDecision === 'approved' ? 'border-green-500 bg-green-100 dark:bg-green-800' : 'border-gray-300 dark:border-gray-600' }}">
                    <input type="radio" wire:model.live="applicationFinalDecision" value="approved" class="mt-1 mr-3">
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 dark:text-gray-100">Лицензия одобрена</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Все требования выполнены, лицензия выдается</div>
                    </div>
                </label>

                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer {{ $applicationFinalDecision === 'revoked' ? 'border-red-500 bg-red-100 dark:bg-red-800' : 'border-gray-300 dark:border-gray-600' }}">
                    <input type="radio" wire:model.live="applicationFinalDecision" value="revoked" class="mt-1 mr-3">
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 dark:text-gray-100">Лицензия отозвана</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Лицензия отзывается</div>
                    </div>
                </label>
            </div>

            <!-- Submit Button -->
            @if($applicationFinalDecision)
                <button
                    wire:click="changeApplicationStatus"
                    wire:confirm="Вы уверены, что хотите изменить статус заявки?"
                    class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium py-3 px-6 rounded-lg transition-colors inline-flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    Применить решение
                </button>
            @endif
        </div>
        @endif

        <!-- Tabs Section -->
        @if(!empty($criteriaTabs))
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
            <!-- Tab Headers -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex -mb-px overflow-x-auto scrollbar-thin scrollbar-thumb-indigo-600 dark:scrollbar-thumb-indigo-700 scrollbar-track-indigo-100 dark:scrollbar-track-indigo-900 hover:scrollbar-thumb-indigo-500 dark:hover:scrollbar-thumb-indigo-600">
                    @foreach($criteriaTabs as $tab)
                        <button
                            wire:click="setActiveTab('{{ $tab['category']->id }}')"
                            class="{{ $activeTab === $tab['category']->id ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}
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
                        <!-- Criterion Status and Actions -->
                        <div class="mb-6 bg-gray-50 dark:bg-gray-900 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                    Статус критерия: {{ $activeCategory['title'] }}
                                </h3>
                                <div class="flex items-center space-x-4">
                                    <span class="{{ $this->getCriterionStatusColor($criterion) }} px-3 py-1 rounded-full text-xs font-medium">
                                        @if($criterion->is_first_passed === false || $criterion->is_industry_passed === false || $criterion->is_final_passed === false)
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

                            <!-- Review Actions based on status -->
                            @if($this->canReviewCriterion($criterion))
                                @php
                                    $statusValue = $criterion->application_status->value ?? null;
                                @endphp

                                <!-- Comment field for criterion -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                        Комментарий по критерию
                                    </label>
                                    <textarea
                                        wire:model="criterionComment"
                                        rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                        placeholder="Введите общий комментарий по критерию..."></textarea>
                                </div>

                                <!-- Action buttons based on status - ONLY show if all documents reviewed -->
                                @if($this->allDocumentsReviewed($criterion->id))
                                    <div class="flex items-center space-x-3">
                                        @if($statusValue === 'awaiting-first-check')
                                            <button
                                                wire:click="submitFirstCheck({{ $criterion->id }}, 'revision')"
                                                wire:confirm="Отправить на доработку?"
                                                class="bg-yellow-600 hover:bg-yellow-700 dark:bg-yellow-500 dark:hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                                                <i class="fas fa-undo mr-2"></i>
                                                Отправить на доработку
                                            </button>
                                            <button
                                                wire:click="submitFirstCheck({{ $criterion->id }}, 'approve')"
                                                wire:confirm="Отправить на отраслевое рассмотрение?"
                                                class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                                                <i class="fas fa-arrow-right mr-2"></i>
                                                Отправить на отраслевое рассмотрение
                                            </button>
                                        @elseif($statusValue === 'awaiting-industry-check')
                                            <button
                                                wire:click="submitIndustryCheck({{ $criterion->id }}, 'revision')"
                                                wire:confirm="Отправить на доработку?"
                                                class="bg-yellow-600 hover:bg-yellow-700 dark:bg-yellow-500 dark:hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                                                <i class="fas fa-undo mr-2"></i>
                                                Отправить на доработку
                                            </button>
                                            <button
                                                wire:click="submitIndustryCheck({{ $criterion->id }}, 'approve')"
                                                wire:confirm="Отправить на контрольное рассмотрение?"
                                                class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                                                <i class="fas fa-arrow-right mr-2"></i>
                                                Отправить на контрольное рассмотрение
                                            </button>
                                        @elseif($statusValue === 'awaiting-control-check')
                                            <button
                                                wire:click="submitControlCheck({{ $criterion->id }}, 'revision')"
                                                wire:confirm="Отправить на доработку?"
                                                class="bg-yellow-600 hover:bg-yellow-700 dark:bg-yellow-500 dark:hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                                                <i class="fas fa-undo mr-2"></i>
                                                Отправить на доработку
                                            </button>
                                            <button
                                                wire:click="submitControlCheck({{ $criterion->id }}, 'approve')"
                                                wire:confirm="Отправить на финальное решение?"
                                                class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                                                <i class="fas fa-arrow-right mr-2"></i>
                                                Отправить на финальное решение
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                                        <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            Необходимо принять решение по всем документам перед отправкой критерия на следующий этап.
                                        </p>
                                    </div>
                                @endif
                            @endif

                            <!-- Comments from Previous Reviews -->
                            @if($criterion->first_comment || $criterion->industry_comment || $criterion->final_comment || $criterion->last_comment)
                                <div class="mt-4 space-y-3">
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        <i class="fas fa-comments mr-2"></i>Комментарии от предыдущих проверок
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

                            <!-- Upgrade to Fully Approved (2.4.4) -->
                            @if($application->application_status_category->value === 'approved' &&
                                $criterion->application_status &&
                                $criterion->application_status->value === 'partially-approved')
                                <div class="mt-4 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                                <i class="fas fa-arrow-up text-blue-600 dark:text-blue-400 mr-2"></i>
                                                Изменить на полностью одобренный
                                            </h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-3">
                                                Этот критерий одобрен частично. Если требования выполнены, вы можете изменить статус на "Полностью одобрено".
                                            </p>
                                            <button
                                                wire:click="upgradeCriterionToFullyApproved({{ $criterion->id }})"
                                                wire:confirm="Вы уверены, что хотите изменить статус критерия на 'Полностью одобрено'?"
                                                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                                                <i class="fas fa-check-double mr-2"></i>
                                                Изменить на полностью одобренный
                                            </button>
                                        </div>
                                    </div>
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
                                                    <i class="fas fa-file-alt text-indigo-500 mr-2"></i>
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
                                        </div>

                                        <!-- Uploaded Documents List with Review Buttons -->
                                        <div class="mt-4 space-y-3">
                                            @if(!empty($uploadedDocs))
                                                @foreach($uploadedDocs as $appDoc)
                                                    @php
                                                        $doc = is_array($appDoc) ? (object)$appDoc : $appDoc;
                                                        $statusValue = $criterion->application_status->value ?? null;

                                                        // Determine if can review based on status (п. 2.1.1.1, 2.2.1.1, 2.3.1.1)
                                                        $canReviewThisDoc = false;
                                                        if ($statusValue === 'awaiting-first-check' &&
                                                            $doc->is_first_passed === null &&
                                                            $doc->is_industry_passed === null &&
                                                            $doc->is_final_passed === null) {
                                                            $canReviewThisDoc = $this->canReviewCriterion($criterion);
                                                        } elseif ($statusValue === 'awaiting-industry-check' &&
                                                                  $doc->is_first_passed === true &&
                                                                  $doc->is_industry_passed === null &&
                                                                  $doc->is_final_passed === null) {
                                                            $canReviewThisDoc = $this->canReviewCriterion($criterion);
                                                        } elseif ($statusValue === 'awaiting-control-check' &&
                                                                  $doc->is_first_passed === true &&
                                                                  $doc->is_industry_passed === true &&
                                                                  $doc->is_final_passed === null) {
                                                            $canReviewThisDoc = $this->canReviewCriterion($criterion);
                                                        }

                                                        $hasDecision = isset($reviewDecisions[$doc->id]);
                                                        $decision = $hasDecision ? $reviewDecisions[$doc->id] : null;
                                                    @endphp
                                                    <div class="p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-600 transition-colors cursor-pointer" wire:click="openDocumentInfoModal({{ $doc->id }})">
                                                        <div class="flex items-start justify-between">
                                                            <div class="flex items-start flex-1">
                                                                <i class="fas fa-file text-gray-400 mr-3 mt-1"></i>
                                                                <div class="flex-1">
                                                                    <div class="flex items-center mb-2">
                                                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                            {{ $doc->title ?? 'Документ' }}
                                                                        </span>

                                                                        <!-- Status badges -->
                                                                        @if($doc->is_first_passed === false)
                                                                            <span class="ml-2 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-1 rounded text-xs font-medium">
                                                                                <i class="fas fa-times mr-1"></i>Не прошел первичную
                                                                            </span>
                                                                        @elseif($doc->is_industry_passed === false)
                                                                            <span class="ml-2 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-1 rounded text-xs font-medium">
                                                                                <i class="fas fa-times mr-1"></i>Не прошел отраслевую
                                                                            </span>
                                                                        @elseif($doc->is_final_passed === false)
                                                                            <span class="ml-2 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-1 rounded text-xs font-medium">
                                                                                <i class="fas fa-times mr-1"></i>Не прошел контрольную
                                                                            </span>
                                                                        @elseif($doc->is_first_passed === null && $doc->is_industry_passed === null && $doc->is_final_passed === null)
                                                                            <span class="ml-2 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-2 py-1 rounded text-xs font-medium">
                                                                                <i class="fas fa-clock mr-1"></i>Ожидает проверки
                                                                            </span>
                                                                        @elseif($doc->is_first_passed === true && $doc->is_industry_passed === true && $doc->is_final_passed === true)
                                                                            <span class="ml-2 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-2 py-1 rounded text-xs font-medium">
                                                                                <i class="fas fa-check mr-1"></i>Все этапы пройдены
                                                                            </span>
                                                                        @elseif($doc->is_first_passed === true && $doc->is_industry_passed === null)
                                                                            <span class="ml-2 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-2 py-1 rounded text-xs font-medium">
                                                                                <i class="fas fa-check mr-1"></i>Прошел первичную
                                                                            </span>
                                                                        @elseif($doc->is_first_passed === true && $doc->is_industry_passed === true && $doc->is_final_passed === null)
                                                                            <span class="ml-2 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-2 py-1 rounded text-xs font-medium">
                                                                                <i class="fas fa-check mr-1"></i>Прошел первичную и отраслевую
                                                                            </span>
                                                                        @endif

                                                                        <!-- Temporary decision badge -->
                                                                        @if($hasDecision)
                                                                            <span class="ml-2 {{ $decision['decision'] ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }} px-2 py-1 rounded text-xs">
                                                                                <i class="fas {{ $decision['decision'] ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                                                                {{ $decision['decision'] ? 'Будет принят' : 'Будет отклонен' }}
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    @if($doc->info)
                                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ $doc->info }}</p>
                                                                    @endif

                                                                    <!-- Show existing comments -->
                                                                    @if($doc->first_comment)
                                                                        <div class="mt-2 p-2 bg-yellow-50 dark:bg-yellow-900 rounded text-xs">
                                                                            <span class="font-medium">Комментарий первичной проверки:</span> {{ $doc->first_comment }}
                                                                        </div>
                                                                    @endif
                                                                    @if($doc->industry_comment)
                                                                        <div class="mt-2 p-2 bg-orange-50 dark:bg-orange-900 rounded text-xs">
                                                                            <span class="font-medium">Комментарий отраслевой проверки:</span> {{ $doc->industry_comment }}
                                                                        </div>
                                                                    @endif
                                                                    @if($doc->control_comment)
                                                                        <div class="mt-2 p-2 bg-purple-50 dark:bg-purple-900 rounded text-xs">
                                                                            <span class="font-medium">Комментарий контрольной проверки:</span> {{ $doc->control_comment }}
                                                                        </div>
                                                                    @endif

                                                                    <!-- Temporary comment -->
                                                                    @if($hasDecision && $decision['comment'])
                                                                        <div class="mt-2 p-2 bg-blue-50 dark:bg-blue-900 rounded text-xs">
                                                                            <span class="font-medium">Новый комментарий:</span> {{ $decision['comment'] }}
                                                                        </div>
                                                                    @endif

                                                                    <div class="flex items-center space-x-3 mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                                        <span>
                                                                            <i class="fas fa-user mr-1"></i>{{ $doc->uploaded_by ?? 'Неизвестно' }}
                                                                        </span>
                                                                        <span>
                                                                            <i class="fas fa-clock mr-1"></i>{{ isset($doc->created_at) ? (is_string($doc->created_at) ? $doc->created_at : $doc->created_at->format('d.m.Y H:i')) : '-' }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Action buttons -->
                                                            <div class="flex items-center space-x-2 ml-4" onclick="event.stopPropagation()">
                                                                <!-- Download button -->
                                                                @if($doc->file_url)
                                                                    <a href="{{ Storage::url($doc->file_url) }}" target="_blank"
                                                                       class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 p-2"
                                                                       title="Просмотр/Скачать">
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                @endif

                                                                <!-- Review buttons (if can review) -->
                                                                @if($canReviewThisDoc)
                                                                    <button
                                                                        wire:click="openAcceptModal({{ $doc->id }}, '{{ addslashes($doc->title ?? 'Документ') }}')"
                                                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs inline-flex items-center"
                                                                        title="Принять">
                                                                        <i class="fas fa-check"></i>
                                                                    </button>
                                                                    <button
                                                                        wire:click="openRejectModal({{ $doc->id }}, '{{ addslashes($doc->title ?? 'Документ') }}')"
                                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs inline-flex items-center"
                                                                        title="Отклонить">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                @endif
                                                            </div>
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

                <!-- Reports Section -->
                @if($criterion && isset($reportsByCriteria[$criterion->id]))
                    <div class="mt-8 bg-gray-50 dark:bg-gray-900 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                <i class="fas fa-clipboard-list text-indigo-500 mr-2"></i>
                                Отчеты по критерию
                            </h3>
                            <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 px-3 py-1 rounded-full text-sm font-medium">
                                {{ count($reportsByCriteria[$criterion->id]) }} {{ count($reportsByCriteria[$criterion->id]) === 1 ? 'отчет' : (count($reportsByCriteria[$criterion->id]) <= 4 ? 'отчета' : 'отчетов') }}
                            </span>
                        </div>

                        @if(!empty($reportsByCriteria[$criterion->id]))
                            <div class="space-y-4">
                                @foreach($reportsByCriteria[$criterion->id] as $report)
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <i class="fas fa-file-alt text-indigo-500 mr-2"></i>
                                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">
                                                        Отчет #{{ $report->id }}
                                                    </h4>
                                                    <span class="ml-3 text-xs text-gray-500 dark:text-gray-400">
                                                        от {{ $report->created_at->format('d.m.Y H:i') }}
                                                    </span>
                                                </div>

                                                @if($report->application_criterion)
                                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                        Критерий: {{ $report->application_criterion->category_document->title_ru ?? 'Не указан' }}
                                                    </div>
                                                @endif

                                                <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                                    @if($report->application && $report->application->user)
                                                        <span>
                                                            <i class="fas fa-user mr-1"></i>
                                                            {{ $report->application->user->name }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="ml-4">
                                                <button
                                                    wire:click="downloadReport({{ $report->id }})"
                                                    wire:loading.attr="disabled"
                                                    class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-75 disabled:cursor-not-allowed">
                                                    <span wire:loading wire:target="downloadReport({{ $report->id }})">
                                                        <i class="fas fa-spinner fa-spin mr-1.5"></i>
                                                        Генерация...
                                                    </span>
                                                    <span wire:loading.remove wire:target="downloadReport({{ $report->id }})">
                                                        <i class="fas fa-download mr-1.5"></i>
                                                        Скачать отчет
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-clipboard text-3xl mb-2"></i>
                                <p>Пока нет отчетов по этому критерию</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Department Reports Section (General reports with criteria_id = null) -->
        @if(!empty($departmentReports))
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 mt-6">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-building text-indigo-500 mr-2"></i>
                        Общие отчеты департамента
                    </h3>
                    <span class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 px-3 py-1 rounded-full text-sm font-medium">
                        {{ count($departmentReports) }} {{ count($departmentReports) === 1 ? 'отчет' : (count($departmentReports) <= 4 ? 'отчета' : 'отчетов') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @foreach($departmentReports as $report)
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-3">
                                        <div class="bg-purple-100 dark:bg-purple-900 rounded-lg p-3 mr-3">
                                            <i class="fas fa-file-alt text-purple-600 dark:text-purple-400 text-lg"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                Общий отчет #{{ $report->id }}
                                            </h4>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                от {{ $report->created_at->format('d.m.Y H:i') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between ml-14">
                                        <div class="flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                            @if($report->application && $report->application->user)
                                                <span>
                                                    <i class="fas fa-user mr-1"></i>
                                                    {{ $report->application->user->name }}
                                                </span>
                                            @endif
                                            <span class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 px-2 py-1 rounded-full text-xs font-medium">
                                                Общий отчет
                                            </span>
                                        </div>

                                        <button
                                            wire:click="downloadDepartmentReport({{ $report->id }})"
                                            wire:loading.attr="disabled"
                                            class="inline-flex items-center px-3 py-1.5 bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-75 disabled:cursor-not-allowed">
                                            <span wire:loading wire:target="downloadDepartmentReport({{ $report->id }})">
                                                <i class="fas fa-spinner fa-spin mr-1.5"></i>
                                                Генерация...
                                            </span>
                                            <span wire:loading.remove wire:target="downloadDepartmentReport({{ $report->id }})">
                                                <i class="fas fa-download mr-1.5"></i>
                                                Скачать
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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

        <!-- Final Decision Modal (2.4.2) -->
        @if($showFinalDecisionModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click.self="closeFinalDecisionModal">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto scrollbar-thin scrollbar-thumb-blue-600 dark:scrollbar-thumb-blue-700 scrollbar-track-blue-100 dark:scrollbar-track-blue-900 hover:scrollbar-thumb-blue-500 dark:hover:scrollbar-thumb-blue-600">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                <i class="fas fa-gavel mr-2 text-indigo-600"></i>
                                Финальное решение по заявке
                            </h3>
                            <button wire:click="closeFinalDecisionModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <div class="space-y-6">
                            <!-- Decisions and Comments by Criterion -->
                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">
                                    Решение и комментарий по каждому критерию <span class="text-red-500">*</span>
                                </label>
                                <div class="space-y-4 max-h-[600px] overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-lg p-4 scrollbar-thin scrollbar-thumb-gray-400 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-200 dark:scrollbar-track-gray-800 hover:scrollbar-thumb-gray-500 dark:hover:scrollbar-thumb-gray-500">
                                    @foreach($allCriteriaForFinalDecision as $criterion)
                                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border-2 border-gray-200 dark:border-gray-700">
                                            <!-- Criterion Header -->
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex-1">
                                                    <div class="font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                                        {{ $criterion['category_document']['title_ru'] ?? 'Критерий' }}
                                                    </div>
                                                    @if(isset($criterion['application_status']))
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            Текущий статус: {{ $criterion['application_status']['title_ru'] ?? '' }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Decision Selection -->
                                            <div class="mb-3">
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    Решение <span class="text-red-500">*</span>
                                                </label>
                                                <div class="space-y-2">
                                                    <label class="flex items-start p-3 border-2 rounded-lg cursor-pointer {{ ($finalDecisionsByCriterion[$criterion['id']] ?? '') === 'fully-approved' ? 'border-green-500 bg-green-50 dark:bg-green-900' : 'border-gray-300 dark:border-gray-600' }}">
                                                        <input type="radio" wire:model="finalDecisionsByCriterion.{{ $criterion['id'] }}" value="fully-approved" class="mt-1 mr-2">
                                                        <div class="flex-1">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Полностью одобрено</div>
                                                            <div class="text-xs text-gray-600 dark:text-gray-400">Критерий полностью соответствует требованиям</div>
                                                        </div>
                                                    </label>

                                                    <label class="flex items-start p-3 border-2 rounded-lg cursor-pointer {{ ($finalDecisionsByCriterion[$criterion['id']] ?? '') === 'partially-approved' ? 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900' : 'border-gray-300 dark:border-gray-600' }}">
                                                        <input type="radio" wire:model="finalDecisionsByCriterion.{{ $criterion['id'] }}" value="partially-approved" class="mt-1 mr-2">
                                                        <div class="flex-1">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Одобрено частично</div>
                                                            <div class="text-xs text-gray-600 dark:text-gray-400">Требуется повторная загрузка документов</div>
                                                        </div>
                                                    </label>

                                                    <label class="flex items-start p-3 border-2 rounded-lg cursor-pointer {{ ($finalDecisionsByCriterion[$criterion['id']] ?? '') === 'revoked' ? 'border-red-500 bg-red-50 dark:bg-red-900' : 'border-gray-300 dark:border-gray-600' }}">
                                                        <input type="radio" wire:model="finalDecisionsByCriterion.{{ $criterion['id'] }}" value="revoked" class="mt-1 mr-2">
                                                        <div class="flex-1">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Отозвано</div>
                                                            <div class="text-xs text-gray-600 dark:text-gray-400">Критерий не соответствует требованиям</div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Documents for reupload (only for partially-approved) -->
                                            @if(($finalDecisionsByCriterion[$criterion['id']] ?? '') === 'partially-approved')
                                                <div class="mb-3 p-3 bg-yellow-50 dark:bg-yellow-900 rounded-lg border border-yellow-200 dark:border-yellow-700">
                                                    <label class="block text-xs font-medium text-gray-900 dark:text-gray-100 mb-2">
                                                        Документы для повторной загрузки <span class="text-red-500">*</span>
                                                    </label>
                                                    <div class="max-h-40 overflow-y-auto space-y-1 scrollbar-thin scrollbar-thumb-gray-400 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-200 dark:scrollbar-track-gray-800 hover:scrollbar-thumb-gray-500 dark:hover:scrollbar-thumb-gray-500">
                                                        @foreach($availableDocumentsForReupload as $doc)
                                                            <label class="flex items-center">
                                                                <input
                                                                    type="checkbox"
                                                                    wire:model="reuploadDocumentIdsByCriterion.{{ $criterion['id'] }}"
                                                                    value="{{ $doc['id'] }}"
                                                                    class="mr-2">
                                                                <span class="text-xs text-gray-900 dark:text-gray-100">{{ $doc['title_ru'] }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Comment -->
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    Комментарий <span class="text-red-500">*</span>
                                                </label>
                                                <textarea
                                                    wire:model="finalCommentsByCriterion.{{ $criterion['id'] }}"
                                                    rows="3"
                                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm"
                                                    placeholder="Введите комментарий по этому критерию..."></textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button
                                type="button"
                                wire:click="closeFinalDecisionModal"
                                class="px-6 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">
                                Отмена
                            </button>
                            <button
                                wire:click="submitFinalDecision"
                                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-lg transition-colors inline-flex items-center">
                                <i class="fas fa-check mr-2"></i>
                                Принять решение
                            </button>
                        </div>
                    </div>
                </div>
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
            <a href="{{ route('department.applications') }}" class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Вернуться к заявкам
            </a>
        </div>
    </div>
    @endif

    <!-- Accept Modal -->
    @if($showAcceptModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click.self="closeAcceptModal">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full mx-4 transform transition-all">
                <div class="p-6">
                    <div class="flex items-center justify-center mb-4">
                        <div class="bg-green-100 dark:bg-green-900 rounded-full p-4">
                            <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-4xl"></i>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 text-center mb-2">
                        Принять документ?
                    </h3>

                    <p class="text-gray-600 dark:text-gray-400 text-center mb-6">
                        Вы уверены, что хотите <span class="font-semibold text-green-600 dark:text-green-400">принять</span> документ<br>
                        <span class="font-medium text-gray-900 dark:text-gray-100">"{{ $currentDocumentTitle }}"</span>?
                    </p>

                    <div class="flex items-center space-x-3">
                        <button
                            wire:click="closeAcceptModal"
                            class="flex-1 px-6 py-3 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium border border-gray-300 dark:border-gray-600 rounded-lg transition-colors">
                            Отмена
                        </button>
                        <button
                            wire:click="confirmAccept"
                            class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white font-medium rounded-lg transition-colors inline-flex items-center justify-center">
                            <i class="fas fa-check mr-2"></i>
                            Принять
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Reject Modal -->
    @if($showRejectModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click.self="closeRejectModal">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full mx-4 transform transition-all">
                <div class="p-6">
                    <div class="flex items-center justify-center mb-4">
                        <div class="bg-red-100 dark:bg-red-900 rounded-full p-4">
                            <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-4xl"></i>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 text-center mb-2">
                        Отклонить документ?
                    </h3>

                    <p class="text-gray-600 dark:text-gray-400 text-center mb-6">
                        Укажите причину отклонения документа<br>
                        <span class="font-medium text-gray-900 dark:text-gray-100">"{{ $currentDocumentTitle }}"</span>
                    </p>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                            Причина отклонения <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            wire:model="rejectComment"
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                            placeholder="Введите причину отклонения документа..."
                            autofocus></textarea>
                        @error('rejectComment')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center space-x-3">
                        <button
                            wire:click="closeRejectModal"
                            class="flex-1 px-6 py-3 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium border border-gray-300 dark:border-gray-600 rounded-lg transition-colors">
                            Отмена
                        </button>
                        <button
                            wire:click="confirmReject"
                            class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white font-medium rounded-lg transition-colors inline-flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i>
                            Отклонить
                        </button>
                    </div>
                </div>
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
                        <div class="bg-indigo-100 dark:bg-indigo-900 p-3 rounded-lg mr-4">
                            <i class="fas fa-file-alt text-indigo-600 dark:text-indigo-400 text-xl"></i>
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
                                <i class="fas fa-info-circle text-indigo-500 mr-2"></i>
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
                                               class="text-sm text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
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

    <!-- Reject Application Modal -->
    @if($showRejectApplicationModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-lg w-full">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="bg-red-100 dark:bg-red-900 p-3 rounded-lg mr-4">
                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                Отказать заявку
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Подтверждение отказа заявки #{{ $application->id }}
                            </p>
                        </div>
                    </div>
                    <button wire:click="closeRejectApplicationModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-300">
                                    Вы уверены?
                                </h3>
                                <div class="mt-2 text-sm text-red-700 dark:text-red-400">
                                    <p>Это действие отклонит всю заявку и все её критерии.</p>
                                    <p class="mt-1">Заявка получит статус "Отказано", и все критерии будут отклонены.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comment field -->
                    <div>
                        <label for="rejectApplicationComment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Комментарий (необязательно)
                        </label>
                        <textarea
                            wire:model="rejectApplicationComment"
                            id="rejectApplicationComment"
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                   focus:ring-2 focus:ring-red-500 focus:border-red-500
                                   bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                   placeholder-gray-500 dark:placeholder-gray-400"
                            placeholder="Укажите причину отказа (необязательно)..."></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200 dark:border-gray-700">
                    <button
                        wire:click="closeRejectApplicationModal"
                        class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Отмена
                    </button>
                    <button
                        wire:click="rejectApplication"
                        class="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center">
                        <i class="fas fa-ban mr-2"></i>
                        Отказать заявку
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
