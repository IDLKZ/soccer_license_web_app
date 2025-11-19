<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Управление дедлайнами критериев заявок</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Управление сроками доработки документов для критериев заявок</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-search mr-1 text-gray-400 dark:text-gray-500"></i>
                    Поиск
                </label>
                <input type="text"
                       wire:model.live.debounce.500ms="search"
                       placeholder="Поиск по клубу, лицензии или категории..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-file-alt mr-1 text-gray-400 dark:text-gray-500"></i>
                    Заявка
                </label>
                <select wire:model.live="filterApplication"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                    <option value="">Все заявки</option>
                    @foreach($applications as $application)
                    <option value="{{ $application->id }}">
                        {{ $application->club?->short_name_ru ?? 'Неизвестно' }} - {{ $application->licence?->title_ru ?? 'Неизвестно' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-info-circle mr-1 text-gray-400 dark:text-gray-500"></i>
                    Статус
                </label>
                <select wire:model.live="filterStatus"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 transition-colors">
                    <option value="">Все статусы</option>
                    @foreach($applicationStatuses as $status)
                    <option value="{{ $status->id }}">{{ $status->title_ru }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if($deadlines->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg dark:shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Заявка
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Критерий
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Статус
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Период
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Статус дедлайна
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Действия
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($deadlines as $deadline)
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/30 transition-all duration-150">
                        <td class="px-4 py-4">
                            @if($deadline->application)
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $deadline->application?->club?->short_name_ru ?? 'Неизвестно' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $deadline->application?->licence?->title_ru ?? 'Неизвестно' }}
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @if($deadline->application_criterion)
                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ $deadline->application_criterion?->category_document?->title_ru ?? 'Неизвестно' }}
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @if($deadline->application_status)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700">
                                {{ $deadline->application_status->title_ru }}
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($deadline->deadline_start_at)
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                <i class="fas fa-hourglass-start text-blue-500 mr-1"></i>
                                {{ $deadline->deadline_start_at->format('d.m.Y H:i') }}
                            </div>
                            @endif
                            @if($deadline->deadline_end_at)
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <i class="fas fa-hourglass-end text-red-500 mr-1"></i>
                                {{ $deadline->deadline_end_at->format('d.m.Y H:i') }}
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            @php
                                $now = now();
                                $isPast = $deadline->deadline_end_at && $now->greaterThan($deadline->deadline_end_at);
                                $isActive = $deadline->deadline_end_at && (
                                    $deadline->deadline_start_at
                                        ? $now->between($deadline->deadline_start_at, $deadline->deadline_end_at)
                                        : $now->lessThanOrEqualTo($deadline->deadline_end_at)
                                );
                                $isFuture = $deadline->deadline_start_at && $deadline->deadline_end_at && $now->lessThan($deadline->deadline_start_at);
                            @endphp
                            @if($isPast)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-rose-100 dark:from-red-900/30 dark:to-rose-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-700">
                                <i class="fas fa-times-circle mr-1"></i>
                                Просрочен
                            </span>
                            @elseif($isActive)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-700">
                                <i class="fas fa-circle-check mr-1"></i>
                                Активен
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-700">
                                <i class="fas fa-hourglass-start mr-1"></i>
                                Ожидает
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($canEdit)
                                <button wire:click="editDeadline({{ $deadline->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/30 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-150"
                                        title="Редактировать">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                @endif
                                @if($canDelete)
                                <button wire:click="deleteDeadline({{ $deadline->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/30 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150"
                                        title="Удалить"
                                        onclick="return confirm('Вы уверены, что хотите удалить этот дедлайн?')">
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

    <!-- Pagination -->
    @if($deadlines->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $deadlines->links('pagination::livewire-tailwind') }}
    </div>
    @endif
    @else
    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="flex flex-col items-center">
            <i class="fas fa-calendar-xmark text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="text-gray-600 dark:text-gray-400 text-lg">Дедлайны не найдены</p>
            <p class="text-gray-500 dark:text-gray-500 text-sm mt-1">
                Попробуйте изменить параметры поиска
            </p>
        </div>
    </div>
    @endif

    <!-- Edit Modal -->
    @if($showEditModal)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4" wire:click.self="closeEditModal">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    <i class="fas fa-edit mr-2 text-indigo-600 dark:text-indigo-400"></i>
                    Редактировать дедлайн
                </h3>
                <button wire:click="closeEditModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form wire:submit.prevent="updateDeadline" class="p-6">
                <div class="space-y-4">
                    <!-- Application Select -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Заявка <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="applicationId"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Выберите заявку</option>
                            @foreach($applications as $app)
                            <option value="{{ $app->id }}">
                                {{ $app->club?->short_name_ru ?? 'Неизвестно' }} - {{ $app->licence?->title_ru ?? 'Неизвестно' }}
                            </option>
                            @endforeach
                        </select>
                        @error('applicationId')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Criterion Select -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Критерий <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="applicationCriteriaId"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                @if(empty($criteriaByApplication)) disabled @endif>
                            <option value="">Выберите критерий</option>
                            @foreach($criteriaByApplication as $criterion)
                            <option value="{{ $criterion->id }}">
                                {{ $criterion->category_document?->title_ru ?? 'Неизвестно' }}
                            </option>
                            @endforeach
                        </select>
                        @error('applicationCriteriaId')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Select -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Статус <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="statusId"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Выберите статус</option>
                            @foreach($applicationStatuses as $status)
                            <option value="{{ $status->id }}">{{ $status->title_ru }}</option>
                            @endforeach
                        </select>
                        @error('statusId')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date (Optional) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Начало периода (необязательно)
                        </label>
                        <input type="datetime-local"
                               wire:model="deadlineStartAt"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('deadlineStartAt')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date (Required) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Крайний срок <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local"
                               wire:model="deadlineEndAt"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('deadlineEndAt')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="button"
                            wire:click="closeEditModal"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-lg transition-colors duration-150">
                        Отмена
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-150">
                        <i class="fas fa-save mr-2"></i>
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
