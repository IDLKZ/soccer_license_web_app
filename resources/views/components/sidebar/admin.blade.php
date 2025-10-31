<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-2xl transition-transform duration-300 ease-in-out transform -translate-x-full lg:translate-x-0">
    <div class="h-full flex flex-col">
        <!-- Logo -->
        <div class="flex items-center justify-center h-20 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shield-halved text-white text-xl"></i>
                </div>
                <span class="ml-3 text-xl font-bold text-gray-900 dark:text-white">КФФ Админ</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-3">
            <div class="space-y-1">
                <!-- Dashboard -->
                <a href="/dashboard" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors group">
                    <i class="fas fa-chart-line w-5 text-center text-blue-500 dark:text-blue-400 group-hover:text-blue-600 dark:group-hover:text-blue-300"></i>
                    <span class="ml-3 font-medium">Панель управления</span>
                </a>

                <!-- Система -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Система</p>
                </div>

                <a href="{{ route('admin.users') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors group {{ request()->routeIs('admin.users') ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : '' }}">
                    <i class="fas fa-users w-5 text-center text-indigo-500 dark:text-indigo-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-300"></i>
                    <span class="ml-3 font-medium">Пользователи</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors group">
                    <i class="fas fa-shield-alt w-5 text-center text-purple-500 dark:text-purple-400 group-hover:text-purple-600 dark:group-hover:text-purple-300"></i>
                    <span class="ml-3 font-medium">Роли и права</span>
                </a>

                <!-- Справочники -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Справочники</p>
                </div>

                <a href="#" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors group">
                    <i class="fas fa-calendar w-5 text-center text-green-500 dark:text-green-400 group-hover:text-green-600 dark:group-hover:text-green-300"></i>
                    <span class="ml-3 font-medium">Сезоны</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors group">
                    <i class="fas fa-trophy w-5 text-center text-yellow-500 dark:text-yellow-400 group-hover:text-yellow-600 dark:group-hover:text-yellow-300"></i>
                    <span class="ml-3 font-medium">Лиги</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors group">
                    <i class="fas fa-futbol w-5 text-center text-orange-500 dark:text-orange-400 group-hover:text-orange-600 dark:group-hover:text-orange-300"></i>
                    <span class="ml-3 font-medium">Клубы</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors group">
                    <i class="fas fa-file-alt w-5 text-center text-cyan-500 dark:text-cyan-400 group-hover:text-cyan-600 dark:group-hover:text-cyan-300"></i>
                    <span class="ml-3 font-medium">Документы</span>
                </a>

                <!-- Лицензирование -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Лицензирование</p>
                </div>

                <a href="#" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors group">
                    <i class="fas fa-certificate w-5 text-center text-pink-500 dark:text-pink-400 group-hover:text-pink-600 dark:group-hover:text-pink-300"></i>
                    <span class="ml-3 font-medium">Лицензии</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors group">
                    <i class="fas fa-clipboard-list w-5 text-center text-teal-500 dark:text-teal-400 group-hover:text-teal-600 dark:group-hover:text-teal-300"></i>
                    <span class="ml-3 font-medium">Заявки</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors group">
                    <i class="fas fa-tasks w-5 text-center text-red-500 dark:text-red-400 group-hover:text-red-600 dark:group-hover:text-red-300"></i>
                    <span class="ml-3 font-medium">Статусы заявок</span>
                </a>
            </div>
        </nav>

        <!-- User Menu -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-4 bg-gray-50 dark:bg-gray-900">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name ?? 'Администратор' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Администратор</p>
                </div>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile sidebar overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden transition-opacity duration-300"></div>

<!-- Mobile close button -->
<button id="close-sidebar" class="lg:hidden fixed top-4 right-4 z-50 bg-white dark:bg-gray-800 p-2 rounded-lg shadow-lg">
    <i class="fas fa-times text-gray-600 dark:text-gray-400"></i>
</button>
