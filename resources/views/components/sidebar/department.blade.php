<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-indigo-900 to-indigo-800 dark:from-indigo-950 dark:to-indigo-900 shadow-2xl transition-transform duration-300 ease-in-out transform lg:translate-x-0">
    <div class="h-full flex flex-col">
        <!-- Logo -->
        <div class="flex items-center justify-center h-20 border-b border-indigo-700 dark:border-indigo-800">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-white text-xl"></i>
                </div>
                <span class="ml-3 text-xl font-bold text-white">Департамент</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-3">
            <div class="space-y-1">
                <!-- Dashboard -->
                <a href="/dashboard" class="flex items-center px-4 py-3 text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-chart-line w-5 text-center text-indigo-400 group-hover:text-indigo-300"></i>
                    <span class="ml-3 font-medium">Панель управления</span>
                </a>

                <!-- Заявки -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Лицензирование</p>
                </div>

                <a href="{{ route('department.applications') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800 hover:text-white rounded-lg transition-colors group {{ request()->is('department-applications*') ? 'bg-indigo-800 text-white' : '' }}">
                    <i class="fas fa-file-alt w-5 text-center text-yellow-400 group-hover:text-yellow-300 {{ request()->is('department-applications*') ? 'text-yellow-300' : '' }}"></i>
                    <span class="ml-3 font-medium">Заявки</span>
                </a>

                <a href="{{ route('department.criterias') }}" class="flex items-center justify-between px-4 py-3 text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800 hover:text-white rounded-lg transition-colors group {{ request()->is('department-criterias*') ? 'bg-indigo-800 text-white' : '' }}">
                    <div class="flex items-center">
                        <i class="fas fa-tasks w-5 text-center text-blue-400 group-hover:text-blue-300 {{ request()->is('department-criterias*') ? 'text-blue-300' : '' }}"></i>
                        <span class="ml-3 font-medium">Мои критерии</span>
                    </div>
                    @php
                        $criteriaCount = \App\Livewire\Department\DepartmentCriterias::getCriteriaCheckCount();
                    @endphp
                    @if($criteriaCount > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $criteriaCount }}</span>
                    @endif
                </a>

            </div>
        </nav>

        <!-- User Menu -->
        <div class="border-t border-indigo-700 dark:border-indigo-800 p-4">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-white">{{ auth()->user()->name ?? 'Специалист' }}</p>
                    <p class="text-xs text-gray-400">{{ auth()->user()->role->title_ru ?? 'Департамент' }}</p>
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
