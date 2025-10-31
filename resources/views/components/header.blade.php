@props(['title' => 'Панель управления'])

<header class="bg-white dark:bg-gray-800 shadow-md">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center">
            <button id="sidebar-toggle" class="lg:hidden text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white mr-4">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                {{ $title }}
            </h1>
        </div>
        <div class="flex items-center gap-4">
            <!-- Notifications -->
            <button class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white relative">
                <i class="fas fa-bell text-xl"></i>
                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">3</span>
            </button>

            @if(auth()->user()->isDepartmentUser())
            <!-- Messages (only for departments) -->
            <button class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white relative">
                <i class="fas fa-envelope text-xl"></i>
                <span class="absolute -top-1 -right-1 w-4 h-4 bg-blue-500 rounded-full text-xs text-white flex items-center justify-center">2</span>
            </button>
            @endif

            <!-- Dark Mode Toggle -->
            <button data-theme-toggle class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                <i class="fas fa-moon text-xl"></i>
            </button>

            @if(auth()->user()->isClubUser())
            <!-- Help (only for club users) -->
            <button class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-question-circle text-xl"></i>
            </button>
            @endif
        </div>
    </div>
</header>
