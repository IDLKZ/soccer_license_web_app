@php
    $user = auth()->user();
    $club = $user->club;
    $activeApplications = $club ? \App\Models\Application::where('club_id', $club->id)
        ->whereHas('application_status_category', function($q) {
            $q->whereIn('value', ['document-submission', 'first-check', 'industry-check', 'control-check', 'final-decision']);
        })
        ->count() : 0;
    $completedApplications = $club ? \App\Models\Application::where('club_id', $club->id)
        ->whereHas('application_status_category', function($q) {
            $q->where('value', 'approved');
        })
        ->count() : 0;
    $totalApplications = $club ? \App\Models\Application::where('club_id', $club->id)->count() : 0;
@endphp

<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Добро пожаловать, {{ $user->first_name }}!</h1>
                <p class="text-green-100">{{ $club->title_ru ?? 'Клуб не назначен' }}</p>
            </div>
            @if($club && $club->logo_path)
                <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center">
                    <img src="{{ asset('storage/' . $club->logo_path) }}" alt="Logo" class="w-12 h-12 object-contain">
                </div>
            @else
                <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center">
                    <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                </div>
            @endif
        </div>
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Active Applications -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Активные заявки</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $activeApplications }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Completed Applications -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Одобренные заявки</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $completedApplications }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Applications -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Всего заявок</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalApplications }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Club Information -->
    @if($club)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
            Информация о клубе
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">Название</p>
                <p class="font-medium text-gray-900 dark:text-white mt-1">{{ $club->title_ru }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">Соревнование</p>
                <p class="font-medium text-gray-900 dark:text-white mt-1">{{ $club->league->title_ru ?? 'Не указана' }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">Тип клуба</p>
                <p class="font-medium text-gray-900 dark:text-white mt-1">{{ $club->club_type->title_ru ?? 'Не указан' }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400">Город</p>
                <p class="font-medium text-gray-900 dark:text-white mt-1">{{ $club->city_ru ?? 'Не указан' }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
            Быстрые действия
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('club.applications') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-file-alt text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Мои заявки</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Просмотр всех заявок</p>
                </div>
            </a>

            <a href="{{ route('club.licences') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-certificate text-green-600 dark:text-green-400"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Лицензии</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Доступные лицензии</p>
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
                $recentApplications = $club ? \App\Models\Application::with(['licence.season', 'application_status_category'])
                    ->where('club_id', $club->id)
                    ->latest()
                    ->take(5)
                    ->get() : collect();
            @endphp
            @forelse($recentApplications as $application)
                <a href="{{ route('club.application.detail', $application->id) }}" class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $application->licence?->season?->title_ru ?? 'Заявка' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $application->application_status_category?->title_ru ?? 'Статус неизвестен' }}</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $application->created_at->diffForHumans() }}</span>
                </a>
            @empty
                <p class="text-gray-600 dark:text-gray-400 text-center py-4">Нет заявок</p>
            @endforelse
        </div>
    </div>
</div>
