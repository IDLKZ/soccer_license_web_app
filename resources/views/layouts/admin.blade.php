<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Панель управления - {{ config('app.name', 'Laravel') }}</title>

    <!-- Theme Script (Prevent Flash) -->
    <script>
        // Apply theme immediately to prevent flash
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <x-sidebar.admin />

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <!-- Header -->
            <x-header title="Панель управления" />

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Mobile Sidebar Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const closeSidebar = document.getElementById('close-sidebar');
            const openSidebar = document.getElementById('sidebar-toggle'); // Burger menu button

            // Set initial state for mobile
            if (window.innerWidth < 1024) { // lg breakpoint
                sidebar.style.transform = 'translateX(-100%)';
                sidebarOverlay.style.display = 'none';
            }

            // Open sidebar
            function openMobileSidebar() {
                sidebar.style.transform = 'translateX(0)';
                sidebarOverlay.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }

            // Close sidebar
            function closeMobileSidebar() {
                sidebar.style.transform = 'translateX(-100%)';
                sidebarOverlay.style.display = 'none';
                document.body.style.overflow = '';
            }

            // Event listeners
            if (openSidebar) {
                openSidebar.addEventListener('click', openMobileSidebar);
            }

            if (closeSidebar) {
                closeSidebar.addEventListener('click', closeMobileSidebar);
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeMobileSidebar);
            }

            // Close sidebar when pressing Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
                    closeMobileSidebar();
                }
            });

            // Close sidebar when window is resized to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) { // lg breakpoint
                    closeMobileSidebar();
                }
            });
        });
    </script>
</body>
</html>
