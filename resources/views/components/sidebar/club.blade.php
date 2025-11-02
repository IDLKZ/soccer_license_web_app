<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-green-900 to-green-800 dark:from-green-950 dark:to-green-900 shadow-2xl transition-transform duration-300 ease-in-out transform lg:translate-x-0">
    <div class="h-full flex flex-col">
        <!-- Logo -->
        <div class="flex items-center justify-center h-20 border-b border-green-700 dark:border-green-800">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-futbol text-white text-xl"></i>
                </div>
                <span class="ml-3 text-xl font-bold text-white">Клуб</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-3">
            <div class="space-y-1">
                <!-- Dashboard -->
                <a href="/dashboard" class="flex items-center px-4 py-3 text-gray-300 hover:bg-green-800 dark:hover:bg-green-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-chart-line w-5 text-center text-green-400 group-hover:text-green-300"></i>
                    <span class="ml-3 font-medium">Панель управления</span>
                </a>

                <!-- Мои заявки -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Лицензирование</p>
                </div>

                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-green-800 dark:hover:bg-green-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-file-alt w-5 text-center text-yellow-400 group-hover:text-yellow-300"></i>
                    <span class="ml-3 font-medium">Мои заявки</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-green-800 dark:hover:bg-green-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-plus-circle w-5 text-center text-blue-400 group-hover:text-blue-300"></i>
                    <span class="ml-3 font-medium">Новая заявка</span>
                </a>

                <a href="{{ route('club.licences') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-green-800 dark:hover:bg-green-800 hover:text-white rounded-lg transition-colors group {{ request()->is('my-licences*') || request()->is('licence/*') ? 'bg-green-800 text-white' : '' }}">
                    <i class="fas fa-certificate w-5 text-center text-purple-400 group-hover:text-purple-300 {{ request()->is('my-licences*') || request()->is('licence/*') ? 'text-purple-300' : '' }}"></i>
                    <span class="ml-3 font-medium">Мои лицензии</span>
                    @php
                        $activeLicencesCount = \App\Livewire\Club\MyLicences::getActiveLicencesCount();
                    @endphp
                    @if($activeLicencesCount > 0)
                    <span class="ml-auto px-2 py-1 bg-red-500 text-white text-xs font-bold rounded-full shadow-lg">
                        {{ $activeLicencesCount }}
                    </span>
                    @endif
                </a>

                <!-- Документы -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Документы</p>
                </div>

                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-green-800 dark:hover:bg-green-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-folder-open w-5 text-center text-orange-400 group-hover:text-orange-300"></i>
                    <span class="ml-3 font-medium">Мои документы</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-green-800 dark:hover:bg-green-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-upload w-5 text-center text-cyan-400 group-hover:text-cyan-300"></i>
                    <span class="ml-3 font-medium">Загрузить документ</span>
                </a>

                <!-- Управление клубом -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Клуб</p>
                </div>

                <a href="/club-management" class="flex items-center px-4 py-3 text-gray-300 hover:bg-green-800 dark:hover:bg-green-800 hover:text-white rounded-lg transition-colors group {{ request()->is('club-management') ? 'bg-green-800 text-white' : '' }}">
                    <i class="fas fa-building w-5 text-center text-indigo-400 group-hover:text-indigo-300 {{ request()->is('club-management') ? 'text-indigo-300' : '' }}"></i>
                    <span class="ml-3 font-medium">Мои клубы</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-green-800 dark:hover:bg-green-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-users w-5 text-center text-pink-400 group-hover:text-pink-300"></i>
                    <span class="ml-3 font-medium">Команда</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-gray-300 hover:bg-green-800 dark:hover:bg-green-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-info-circle w-5 text-center text-blue-400 group-hover:text-blue-300"></i>
                    <span class="ml-3 font-medium">Информация</span>
                </a>
            </div>
        </nav>

        <!-- User Menu -->
        <div class="border-t border-green-700 dark:border-green-800 p-4">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-white">{{ auth()->user()->name ?? 'Пользователь' }}</p>
                    <p class="text-xs text-gray-400">Клуб</p>
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
