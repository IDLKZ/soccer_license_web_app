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
            // Wait a bit for all elements to be properly rendered
            setTimeout(initMobileSidebar, 100);
        });

        function initMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const closeSidebar = document.getElementById('close-sidebar');
            const openSidebar = document.getElementById('sidebar-toggle'); // Burger menu button

            // Check if all elements exist
            if (!sidebar || !sidebarOverlay || !closeSidebar || !openSidebar) {
                console.error('Mobile sidebar elements not found');
                return;
            }

            // Set initial state for mobile
            function setInitialState() {
                if (window.innerWidth < 1024) { // lg breakpoint
                    sidebar.style.transform = 'translateX(-100%)';
                    sidebarOverlay.style.display = 'none';
                    closeSidebar.classList.add('hidden');
                    document.body.style.overflow = '';
                } else {
                    sidebar.style.transform = 'translateX(0)';
                    sidebarOverlay.style.display = 'none';
                    closeSidebar.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            }

            // Open sidebar
            function openMobileSidebar() {
                sidebar.style.transform = 'translateX(0)';
                sidebarOverlay.style.display = 'block';
                closeSidebar.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            // Close sidebar
            function closeMobileSidebar() {
                sidebar.style.transform = 'translateX(-100%)';
                sidebarOverlay.style.display = 'none';
                closeSidebar.classList.add('hidden');
                document.body.style.overflow = '';
            }

            // Set initial state
            setInitialState();

            // Event listeners
            openSidebar.addEventListener('click', openMobileSidebar);
            closeSidebar.addEventListener('click', closeMobileSidebar);
            sidebarOverlay.addEventListener('click', closeMobileSidebar);

            // Close sidebar when pressing Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.style.transform === 'translateX(0px)') {
                    closeMobileSidebar();
                }
            });

            // Close sidebar when window is resized
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) { // lg breakpoint
                    sidebar.style.transform = 'translateX(0)';
                    sidebarOverlay.style.display = 'none';
                    closeSidebar.classList.add('hidden');
                    document.body.style.overflow = '';
                } else {
                    // When switching to mobile, hide sidebar
                    if (sidebar.style.transform !== 'translateX(-100%)') {
                        closeMobileSidebar();
                    }
                }
            });
        }
    </script>
</body>
</html>
