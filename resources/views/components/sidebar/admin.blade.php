<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-gray-900 to-gray-800 dark:from-gray-950 dark:to-gray-900 shadow-2xl transition-transform duration-300 ease-in-out transform lg:translate-x-0">
    <div class="h-full flex flex-col">
        <!-- Logo -->
        <div class="flex items-center justify-center h-20 border-b border-gray-700 dark:border-gray-800">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shield-halved text-white text-xl"></i>
                </div>
                <span class="ml-3 text-xl font-bold text-white">КФФ Админ</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-3">
            <div class="space-y-1">
                <!-- Dashboard -->
                <a href="/dashboard" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 dark:hover:bg-gray-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-chart-line w-5 text-center text-blue-400 group-hover:text-blue-300"></i>
                    <span class="ml-3 font-medium">Панель управления</span>
                </a>

                <!-- Система -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Система</p>
                </div>

                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 dark:hover:bg-gray-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-users w-5 text-center text-indigo-400 group-hover:text-indigo-300"></i>
                    <span class="ml-3 font-medium">Пользователи</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 dark:hover:bg-gray-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-shield-alt w-5 text-center text-purple-400 group-hover:text-purple-300"></i>
                    <span class="ml-3 font-medium">Роли и права</span>
                </a>

                <!-- Справочники -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Справочники</p>
                </div>

                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 dark:hover:bg-gray-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-calendar w-5 text-center text-green-400 group-hover:text-green-300"></i>
                    <span class="ml-3 font-medium">Сезоны</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 dark:hover:bg-gray-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-trophy w-5 text-center text-yellow-400 group-hover:text-yellow-300"></i>
                    <span class="ml-3 font-medium">Лиги</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 dark:hover:bg-gray-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-futbol w-5 text-center text-orange-400 group-hover:text-orange-300"></i>
                    <span class="ml-3 font-medium">Клубы</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 dark:hover:bg-gray-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-file-alt w-5 text-center text-cyan-400 group-hover:text-cyan-300"></i>
                    <span class="ml-3 font-medium">Документы</span>
                </a>

                <!-- Лицензирование -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Лицензирование</p>
                </div>

                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 dark:hover:bg-gray-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-certificate w-5 text-center text-pink-400 group-hover:text-pink-300"></i>
                    <span class="ml-3 font-medium">Лицензии</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 dark:hover:bg-gray-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-clipboard-list w-5 text-center text-teal-400 group-hover:text-teal-300"></i>
                    <span class="ml-3 font-medium">Заявки</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 dark:hover:bg-gray-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-tasks w-5 text-center text-red-400 group-hover:text-red-300"></i>
                    <span class="ml-3 font-medium">Статусы заявок</span>
                </a>
            </div>
        </nav>

        <!-- User Menu -->
        <div class="border-t border-gray-700 dark:border-gray-800 p-4">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-white">{{ auth()->user()->name ?? 'Администратор' }}</p>
                    <p class="text-xs text-gray-400">Администратор</p>
                </div>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>
