<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Панель администратора - {{ config('app.name', 'Laravel') }}</title>

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

    <!-- jQuery (required for Summernote) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <x-sidebar.admin />

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <!-- Header -->
            <x-header title="Панель администратора" />

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Bootstrap JS (required for Summernote) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

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

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Toastr Configuration for Dark Mode -->
    <script>
        // Configure Toastr for dark/light theme support
        document.addEventListener('DOMContentLoaded', function() {
            const isDarkMode = document.documentElement.classList.contains('dark');

            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut",
                "toastClass": isDarkMode ? "toastr" : "toastr",
                "titleClass": isDarkMode ? "toastr-title" : "toastr-title",
                "messageClass": isDarkMode ? "toastr-message" : "toastr-message"
            };

            // Listen for Livewire events
            Livewire.on('showMessage', function(event) {
                const { type, message } = event;

                // Update toastr options for current theme
                const currentIsDarkMode = document.documentElement.classList.contains('dark');
                if (currentIsDarkMode) {
                    toastr.options.toastClass = 'toastr-dark';
                } else {
                    toastr.options.toastClass = 'toastr';
                }

                // Show the appropriate toast with smart stacking
                switch(type) {
                    case 'success':
                        toastr.success(message);
                        break;
                    case 'error':
                        // For validation errors, use shorter timeout to avoid overcrowding
                        const originalTimeout = toastr.options.timeOut;
                        toastr.options.timeOut = 4000; // 4 seconds for errors

                        toastr.error(message);

                        // Restore original timeout
                        setTimeout(() => {
                            toastr.options.timeOut = originalTimeout;
                        }, 100);
                        break;
                    case 'warning':
                        toastr.warning(message);
                        break;
                    case 'info':
                        toastr.info(message);
                        break;
                    default:
                        toastr.info(message);
                }
            });
        });

        // Update toastr options when theme changes
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    const isDarkMode = document.documentElement.classList.contains('dark');
                    if (isDarkMode) {
                        toastr.options.toastClass = 'toastr-dark';
                    } else {
                        toastr.options.toastClass = 'toastr';
                    }
                }
            });
        });

        observer.observe(document.documentElement, { attributes: true });
    </script>

    <!-- Custom Dark Mode Toastr Styles -->
    <style>
        .toastr-dark {
            background-color: #1f2937 !important;
            color: #f3f4f6 !important;
            border: 1px solid #374151 !important;
        }

        .toastr-dark .toastr-title {
            color: #f9fafb !important;
        }

        .toastr-dark .toastr-message {
            color: #e5e7eb !important;
        }

        .toastr-dark.toast-success {
            background-color: #065f46 !important;
            border-color: #047857 !important;
        }

        .toastr-dark.toast-error {
            background-color: #7f1d1d !important;
            border-color: #991b1b !important;
        }

        .toastr-dark.toast-warning {
            background-color: #78350f !important;
            border-color: #92400e !important;
        }

        .toastr-dark.toast-info {
            background-color: #1e3a8a !important;
            border-color: #1e40af !important;
        }
    </style>
</body>
</html>
