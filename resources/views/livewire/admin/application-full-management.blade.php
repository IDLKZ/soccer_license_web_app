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

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                    <i class="fas fa-file-alt mr-3 text-indigo-600 dark:text-indigo-400"></i>
                    Управление заявками
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Просмотр и управление всеми заявками на лицензирование
                </p>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-search mr-2"></i>Поиск
                    </label>
                    <input
                        type="text"
                        wire:model.live.debounce.500ms="search"
                        placeholder="ID, клуб, пользователь..."
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-filter mr-2"></i>Статус
                    </label>
                    <select
                        wire:model.live="filterStatus"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">Все статусы</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->title_ru }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Licence Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-certificate mr-2"></i>Лицензия
                    </label>
                    <select
                        wire:model.live="filterLicence"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">Все лицензии</option>
                        @foreach($licences as $licence)
                            <option value="{{ $licence->id }}">
                                {{ $licence->title_ru }} ({{ $licence->season?->title_ru ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Club Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-building mr-2"></i>Клуб
                    </label>
                    <select
                        wire:model.live="filterClub"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">Все клубы</option>
                        @foreach($clubs as $club)
                            <option value="{{ $club->id }}">{{ $club->short_name_ru ?? $club->full_name_ru }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Клуб
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Лицензия
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Ответственный
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Статус
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Дата создания
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Действия
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($applications as $application)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    #{{ $application->id }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $application->club?->short_name_ru ?? $application->club?->full_name_ru ?? '-' }}
                                </div>
                                @if(!$application->club_id)
                                    <div class="text-xs text-red-600 dark:text-red-400">
                                        <i class="fas fa-exclamation-circle"></i> Клуб не назначен
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $application->licence?->title_ru ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $application->licence?->season?->title_ru ?? '-' }}
                                </div>
                                @if(!$application->license_id)
                                    <div class="text-xs text-red-600 dark:text-red-400">
                                        <i class="fas fa-exclamation-circle"></i> Лицензия не назначена
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $application->user?->first_name ?? '' }} {{ $application->user?->last_name ?? '' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $application->user?->email ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusValue = $application->application_status_category?->value ?? 'draft';
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'awaiting-first-check' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'first-check-revision' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'awaiting-industry-check' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                        'industry-check-revision' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
                                        'awaiting-control-check' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
                                        'control-check-revision' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300',
                                        'awaiting-final-decision' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300',
                                        'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'partially-approved' => 'bg-lime-100 text-lime-800 dark:bg-lime-900 dark:text-lime-300',
                                        'revoked' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                    ];
                                    $colorClass = $statusColors[$statusValue] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                    {{ $application->application_status_category?->title_ru ?? 'Не определен' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $application->created_at?->format('d.m.Y') ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $application->created_at?->format('H:i') ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- View Details Button -->
                                    @if($canView)
                                        <button
                                            wire:click="viewDetails({{ $application->id }})"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
                                            title="Просмотр деталей">
                                            <i class="fas fa-eye text-lg"></i>
                                        </button>
                                    @endif

                                    <!-- Delete Button (only if license_id or club_id is null) -->
                                    @if($canDelete && (!$application->license_id || !$application->club_id))
                                        <button
                                            wire:click="openDeleteModal({{ $application->id }})"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                            title="Удалить">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-inbox text-5xl mb-4 text-gray-400 dark:text-gray-600"></i>
                                    <p class="text-lg font-medium">Заявки не найдены</p>
                                    <p class="text-sm mt-2">Попробуйте изменить параметры поиска или фильтры</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $applications->links() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75" aria-hidden="true" wire:click="closeDeleteModal"></div>

                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 rounded-xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                                <i class="fas fa-trash-alt mr-3 text-red-600 dark:text-red-400"></i>
                                Подтверждение удаления
                            </h3>
                            <button wire:click="closeDeleteModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 bg-red-100 dark:bg-red-900 rounded-full">
                                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-700 dark:text-gray-300">
                                    Вы уверены, что хотите удалить эту заявку?
                                </p>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Это действие нельзя отменить. Все связанные данные (критерии, документы, отчеты) будут удалены.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-end space-x-3">
                            <button
                                wire:click="closeDeleteModal"
                                type="button"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                <i class="fas fa-times mr-2"></i>Отмена
                            </button>
                            <button
                                wire:click="deleteApplication"
                                type="button"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 dark:bg-red-500 border border-transparent rounded-lg hover:bg-red-700 dark:hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                <i class="fas fa-trash-alt mr-2"></i>Удалить
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
