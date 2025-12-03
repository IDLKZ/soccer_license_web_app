@php
    use App\Constants\RoleConstants;

    $user = auth()->user();
    $userRole = $user->role->value ?? null;

    // Get applications statistics based on user department
    $pendingApplications = \App\Models\Application::whereHas('application_status_category', function($q) use ($userRole) {
        if ($userRole === RoleConstants::LICENSING_DEPARTMENT_VALUE) {
            $q->where('value', 'first-check');
        } elseif (in_array($userRole, [
            RoleConstants::LEGAL_DEPARTMENT_VALUE,
            RoleConstants::FINANCE_DEPARTMENT_VALUE,
            RoleConstants::INFRASTRUCTURE_DEPARTMENT_VALUE
        ])) {
            $q->where('value', 'industry-check');
        } elseif ($userRole === RoleConstants::CONTROL_DEPARTMENT_VALUE) {
            $q->where('value', 'control-check');
        }
    })->count();

    $totalApplications = \App\Models\Application::count();
    $approvedApplications = \App\Models\Application::whereHas('application_status_category', function($q) {
        $q->where('value', 'approved');
    })->count();
    $rejectedApplications = \App\Models\Application::whereHas('application_status_category', function($q) {
        $q->where('value', 'rejected');
    })->count();

    // Get department name
    $departmentName = match($userRole) {
        RoleConstants::LICENSING_DEPARTMENT_VALUE => 'Отдел лицензирования',
        RoleConstants::LEGAL_DEPARTMENT_VALUE => 'Юридический отдел',
        RoleConstants::FINANCE_DEPARTMENT_VALUE => 'Финансовый отдел',
        RoleConstants::INFRASTRUCTURE_DEPARTMENT_VALUE => 'Отдел инфраструктуры',
        RoleConstants::CONTROL_DEPARTMENT_VALUE => 'Контрольный отдел',
        default => $user->role->title_ru ?? 'Департамент'
    };
@endphp

<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">Добро пожаловать, {{ $user->first_name }}!</h1>
        <p class="text-purple-100">{{ $departmentName }}</p>
    </div>

    <!-- Documents Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-file-pdf text-red-500 mr-2"></i>
            Нормативные документы
        </h2>
        @php
            $documentsPath = base_path('docs.json');
            $documents = file_exists($documentsPath) ? json_decode(file_get_contents($documentsPath), true) : [];
        @endphp

        @if(count($documents) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($documents as $doc)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors group">
                        <div class="flex items-center space-x-3 flex-1 min-w-0">
                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-file-pdf text-red-600 dark:text-red-400"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 dark:text-white truncate">{{ $doc['title'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PDF документ</p>
                            </div>
                        </div>
                        <a href="{{ asset($doc['path']) }}"
                           target="_blank"
                           class="ml-3 flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors"
                           title="Открыть документ">
                            <i class="fas fa-external-link-alt text-blue-600 dark:text-blue-400 text-sm"></i>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600 dark:text-gray-400 text-center py-4">Нет доступных документов</p>
        @endif
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Pending Applications -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">На проверке</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $pendingApplications }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Applications -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Всего заявок</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalApplications }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Approved Applications -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Одобрено</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $approvedApplications }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Rejected Applications -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Отклонено</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $rejectedApplications }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Info -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
            Информация об отделе
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">Ваша роль</p>
                <p class="font-medium text-gray-900 dark:text-white mt-1">{{ $user->role->title_ru ?? 'Не указана' }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">Отдел</p>
                <p class="font-medium text-gray-900 dark:text-white mt-1">{{ $departmentName }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">Email</p>
                <p class="font-medium text-gray-900 dark:text-white mt-1">{{ $user->email }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">Статус</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                    <i class="fas fa-check-circle mr-1"></i> Активен
                </span>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
            Быстрые действия
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('department.applications') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-file-alt text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Все заявки</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Просмотр всех заявок</p>
                </div>
            </a>

            <a href="{{ route('department.applications') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Ожидают проверки</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Заявки на проверке</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Applications -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-clock text-blue-500 mr-2"></i>
            Последние заявки
        </h2>
        <div class="space-y-4">
            @php
                $recentApplications = \App\Models\Application::with(['club', 'licence.season', 'application_status_category'])
                    ->latest()
                    ->take(5)
                    ->get();
            @endphp
            @forelse($recentApplications as $application)
                <a href="{{ route('department.application.detail', $application->id) }}" class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $application->club->title_ru ?? 'Клуб неизвестен' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $application->application_status_category->title_ru ?? 'Статус неизвестен' }}</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $application->created_at->diffForHumans() }}</span>
                </a>
            @empty
                <p class="text-gray-600 dark:text-gray-400 text-center py-4">Нет заявок</p>
            @endforelse
        </div>
    </div>

    <!-- Statistics by Status -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-chart-pie text-purple-500 mr-2"></i>
            Статистика по статусам
        </h2>
        <div class="space-y-3">
            @php
                $statuses = \App\Models\ApplicationStatusCategory::all();
            @endphp
            @foreach($statuses as $status)
                @php
                    $count = \App\Models\Application::where('category_id', $status->id)->count();
                    $percentage = $totalApplications > 0 ? round(($count / $totalApplications) * 100) : 0;
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $status->title_ru }}</span>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $count }} ({{ $percentage }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
