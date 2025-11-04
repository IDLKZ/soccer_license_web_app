@props(['title' => 'Панель'])

<header class="bg-white dark:bg-gray-800 shadow-md">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center">
            <button id="sidebar-toggle" data-sidebar-target class="lg:hidden text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white mr-4">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                {{ $title }}
            </h1>
        </div>
        <div class="flex items-center gap-4">
            <!-- Dark Mode Toggle -->
            <button data-theme-toggle class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                <i class="fas fa-moon text-xl"></i>
            </button>
        </div>
    </div>
</header>
