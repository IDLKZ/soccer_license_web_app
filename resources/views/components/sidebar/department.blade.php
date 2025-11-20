<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-indigo-900 to-indigo-800 dark:from-indigo-950 dark:to-indigo-900 shadow-2xl transition-transform duration-300 ease-in-out transform lg:translate-x-0">
    <div class="h-full flex flex-col">
        <!-- Logo -->
        <div class="flex items-center justify-center h-20 border-b border-indigo-700 dark:border-indigo-800">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <img src="{{ asset('logo_kff.png') }}" alt="KFF Logo" class="w-8 h-8 object-contain">
                </div>
                <span class="ml-3 text-xl font-bold text-white">
                    @if(\Illuminate\Support\Facades\Auth::user()->role->value == \App\Constants\RoleConstants::CONTROL_DEPARTMENT_VALUE)
                        Комиссия
                    @else
                        Департамент
                    @endif
                </span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-6 px-3 scrollbar-thin scrollbar-thumb-indigo-600 dark:scrollbar-thumb-indigo-700 scrollbar-track-indigo-800 dark:scrollbar-track-indigo-900 hover:scrollbar-thumb-indigo-500 dark:hover:scrollbar-thumb-indigo-600">
            <div class="space-y-1">
                <!-- Dashboard -->
                <a href="/dashboard" class="flex items-center px-4 py-3 text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800 hover:text-white rounded-lg transition-colors group">
                    <i class="fas fa-chart-line w-5 text-center text-indigo-400 group-hover:text-indigo-300"></i>
                    <span class="ml-3 font-medium">
                        @if(\Illuminate\Support\Facades\Auth::user()->role->value == \App\Constants\RoleConstants::CONTROL_DEPARTMENT_VALUE)
                            Панель комиссии
                        @else
                            Панель департамента
                        @endif
                    </span>
                </a>
                <!-- Справочники -->
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Бизнес процессы</p>
                </div>
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
                <a href="{{ route('department.applications') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800 hover:text-white rounded-lg transition-colors group {{ request()->is('department-applications*') ? 'bg-indigo-800 text-white' : '' }}">
                    <i class="fas fa-clipboard-list w-5 text-center text-teal-400 group-hover:text-teal-300 {{ request()->is('department-applications*') ? 'text-teal-300' : '' }}"></i>
                    <span class="ml-3 font-medium">Заявки на лицензирование</span>
                </a>

                @can('view-full-application')
                <a href="{{ route('department.all-applications') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800 hover:text-white rounded-lg transition-colors group {{ request()->routeIs('department.all-applications') || request()->routeIs('department.all-application-detailed') ? 'bg-indigo-800 text-white' : '' }}">
                    <i class="fas fa-file-alt w-5 text-center text-indigo-400 group-hover:text-indigo-300 {{ request()->routeIs('department.all-applications') || request()->routeIs('department.all-application-detailed') ? 'text-indigo-300' : '' }}"></i>
                    <span class="ml-3 font-medium">Все заявки</span>
                </a>
                @endcan

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Справочные данные</p>
                </div>
                @can('view-clubs')
                <a href="{{ route('admin.clubs') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800 hover:text-white rounded-lg transition-colors group {{ request()->routeIs('admin.clubs') ? 'bg-indigo-800 text-white' : '' }}">
                    <i class="fas fa-futbol w-5 text-center text-orange-400 group-hover:text-orange-300 {{ request()->routeIs('admin.clubs') ? 'text-orange-300' : '' }}"></i>
                    <span class="ml-3 font-medium">Клубы</span>
                </a>
                @endcan

                @can('view-club-teams')
                <a href="{{ route('admin.club-teams') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800 hover:text-white rounded-lg transition-colors group {{ request()->routeIs('admin.club-teams') ? 'bg-indigo-800 text-white' : '' }}">
                    <i class="fas fa-users w-5 text-center text-emerald-400 group-hover:text-emerald-300 {{ request()->routeIs('admin.club-teams') ? 'text-emerald-300' : '' }}"></i>
                    <span class="ml-3 font-medium">Специалисты клубов</span>
                </a>
                @endcan

                @can('view-category-documents')
                <a href="{{ route('admin.category-documents') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800 hover:text-white rounded-lg transition-colors group {{ request()->routeIs('admin.category-documents') ? 'bg-indigo-800 text-white' : '' }}">
                    <i class="fas fa-folder-open w-5 text-center text-purple-400 group-hover:text-purple-300 {{ request()->routeIs('admin.category-documents') ? 'text-purple-300' : '' }}"></i>
                    <span class="ml-3 font-medium">Категории документов</span>
                </a>
                @endcan

                @can('view-documents')
                <a href="{{ route('admin.documents') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800 hover:text-white rounded-lg transition-colors group {{ request()->routeIs('admin.documents') ? 'bg-indigo-800 text-white' : '' }}">
                    <i class="fas fa-file-alt w-5 text-center text-cyan-400 group-hover:text-cyan-300 {{ request()->routeIs('admin.documents') ? 'text-cyan-300' : '' }}"></i>
                    <span class="ml-3 font-medium">Документы</span>
                </a>
                @endcan

                @can('view-licences')
                <a href="{{ route('admin.licences') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800 hover:text-white rounded-lg transition-colors group {{ request()->routeIs('admin.licences') ? 'bg-indigo-800 text-white' : '' }}">
                    <i class="fas fa-certificate w-5 text-center text-pink-400 group-hover:text-pink-300 {{ request()->routeIs('admin.licences') ? 'text-pink-300' : '' }}"></i>
                    <span class="ml-3 font-medium">Лицензии</span>
                </a>
                @endcan

                @can('view-licence-requirements')
                <a href="{{ route('admin.licence-requirements') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800 hover:text-white rounded-lg transition-colors group {{ request()->routeIs('admin.licence-requirements') ? 'bg-indigo-800 text-white' : '' }}">
                    <i class="fas fa-list-check w-5 text-center text-purple-400 group-hover:text-purple-300 {{ request()->routeIs('admin.licence-requirements') ? 'text-purple-300' : '' }}"></i>
                    <span class="ml-3 font-medium">Требования к лицензиям</span>
                </a>
                @endcan

                @can('view-licence-deadlines')
                <a href="{{ route('admin.licence-deadlines') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800 hover:text-white rounded-lg transition-colors group {{ request()->routeIs('admin.licence-deadlines') ? 'bg-indigo-800 text-white' : '' }}">
                    <i class="fas fa-calendar-days w-5 text-center text-orange-400 group-hover:text-orange-300 {{ request()->routeIs('admin.licence-deadlines') ? 'text-orange-300' : '' }}"></i>
                    <span class="ml-3 font-medium">Дедлайны лицензий</span>
                </a>
                @endcan

                @can('view-application-criteria-deadline')
                    <a href="{{ route('admin.application-criteria-deadlines') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800 hover:text-white rounded-lg transition-colors group {{ request()->routeIs('admin.application-criteria-deadlines') ? 'bg-indigo-800 text-white' : '' }}">
                        <i class="fas fa-calendar-check w-5 text-center text-teal-500 dark:text-teal-400 group-hover:text-teal-600 dark:group-hover:text-teal-300"></i>
                        <span class="ml-3 font-medium">Дедлайны критериев</span>
                    </a>
                @endcan

                @can('view-application-status-categories')
                <a href="{{ route('admin.application-status-categories') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800 hover:text-white rounded-lg transition-colors group {{ request()->routeIs('admin.application-status-categories') ? 'bg-indigo-800 text-white' : '' }}">
                    <i class="fas fa-tasks w-5 text-center text-red-400 group-hover:text-red-300 {{ request()->routeIs('admin.application-status-categories') ? 'text-red-300' : '' }}"></i>
                    <span class="ml-3 font-medium">Категории статусов</span>
                </a>
                @endcan

                @can('view-application-statuses')
                <a href="{{ route('admin.application-statuses') }}" class="flex items-center px-4 py-3 text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800 hover:text-white rounded-lg transition-colors group {{ request()->routeIs('admin.application-statuses') ? 'bg-indigo-800 text-white' : '' }}">
                    <i class="fas fa-list-check w-5 text-center text-amber-400 group-hover:text-amber-300 {{ request()->routeIs('admin.application-statuses') ? 'text-amber-300' : '' }}"></i>
                    <span class="ml-3 font-medium">Статусы заявок</span>
                </a>
                @endcan



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
