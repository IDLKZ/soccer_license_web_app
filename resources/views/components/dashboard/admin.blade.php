<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">Добро пожаловать, Администратор!</h1>
        <p class="text-blue-100">Управляйте системой лицензирования футбольных клубов</p>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Всего пользователей</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ \App\Models\User::count() }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-indigo-600 dark:text-indigo-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Clubs -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Всего клубов</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ \App\Models\Club::count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shield-alt text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Licenses -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Активные лицензии</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ \App\Models\Licence::count() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-certificate text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Applications -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Всего заявок</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ \App\Models\Application::count() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
            Быстрые действия
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('admin.users') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user-plus text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Добавить пользователя</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Создать нового пользователя</p>
                </div>
            </a>

            <a href="{{ route('admin.clubs') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-plus-circle text-green-600 dark:text-green-400"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Добавить клуб</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Зарегистрировать новый клуб</p>
                </div>
            </a>

            <a href="{{ route('admin.licences') }}" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-file-contract text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Управление лицензиями</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Создать и настроить лицензии</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-clock text-blue-500 mr-2"></i>
            Последняя активность
        </h2>
        <div class="space-y-4">
            @php
                $recentUsers = \App\Models\User::with('role')->latest()->take(5)->get();
            @endphp
            @forelse($recentUsers as $user)
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold">{{ substr($user->first_name ?? 'U', 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $user->first_name }} {{ $user->last_name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->role->title_ru ?? 'Без роли' }}</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p class="text-gray-600 dark:text-gray-400 text-center py-4">Нет последней активности</p>
            @endforelse
        </div>
    </div>
</div>
