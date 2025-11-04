<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

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
        <x-sidebar.department />

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <!-- Header -->
            <x-header title="Панель департамента" />

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

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

    <!-- Summernote Custom Styles (No Bootstrap) -->
    <style>
        /* Custom Summernote styles for Tailwind CSS without Bootstrap */
        .note-editor {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: white;
        }

        .dark .note-editor {
            border-color: #4b5563;
            background-color: #1f2937;
        }

        .note-editor .note-toolbar {
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .dark .note-editor .note-toolbar {
            background-color: #374151;
            border-bottom-color: #4b5563;
        }

        .note-editor .note-toolbar .btn-group {
            margin: 1px 0;
        }

        .note-editor .note-toolbar .btn {
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            color: #374151;
            padding: 0.375rem 0.5rem;
            margin: 0 1px;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            transition: all 0.15s ease-in-out;
        }

        .dark .note-editor .note-toolbar .btn {
            background-color: #4b5563;
            border-color: #6b7280;
            color: #e5e7eb;
        }

        .note-editor .note-toolbar .btn:hover {
            background-color: #f3f4f6;
            border-color: #9ca3af;
        }

        .dark .note-editor .note-toolbar .btn:hover {
            background-color: #6b7280;
            border-color: #9ca3af;
        }

        .note-editor .note-toolbar .btn.active {
            background-color: #3b82f6;
            border-color: #2563eb;
            color: white;
        }

        .note-editor .note-toolbar .dropdown-menu {
            background-color: white;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .dark .note-editor .note-toolbar .dropdown-menu {
            background-color: #1f2937;
            border-color: #4b5563;
        }

        .note-editor .note-toolbar .dropdown-menu > li > a {
            color: #374151;
            padding: 0.5rem 0.75rem;
            transition: all 0.15s ease-in-out;
        }

        .dark .note-editor .note-toolbar .dropdown-menu > li > a {
            color: #e5e7eb;
        }

        .note-editor .note-toolbar .dropdown-menu > li > a:hover {
            background-color: #f3f4f6;
        }

        .dark .note-editor .note-toolbar .dropdown-menu > li > a:hover {
            background-color: #374151;
        }

        .note-editor .note-editable {
            padding: 1rem;
            min-height: 150px;
            color: #111827;
            background-color: white;
        }

        .dark .note-editor .note-editable {
            color: #e5e7eb;
            background-color: #1f2937;
        }

        .note-editor .note-statusbar {
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
            color: #6b7280;
            font-size: 0.75rem;
        }

        .dark .note-editor .note-statusbar {
            background-color: #374151;
            border-top-color: #4b5563;
            color: #9ca3af;
        }

        .note-popover {
            background-color: white;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .dark .note-popover {
            background-color: #1f2937;
            border-color: #4b5563;
        }

        /* Modal styles */
        .note-modal {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .dark .note-modal {
            background-color: #1f2937;
        }

        .note-modal .note-modal-header {
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .dark .note-modal .note-modal-header {
            background-color: #374151;
            border-bottom-color: #4b5563;
        }

        .note-modal .note-modal-body {
            padding: 1rem;
        }

        .note-modal .note-modal-footer {
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 1rem;
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }

        .dark .note-modal .note-modal-footer {
            background-color: #374151;
            border-top-color: #4b5563;
        }
    </style>

    <!-- Summernote Initialization Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Summernote on textareas with data-summernote attribute
            const textareas = document.querySelectorAll('textarea[data-summernote]');

            textareas.forEach(function(textarea) {
                // Check if already initialized
                if (!textarea.classList.contains('summernote-initialized')) {
                    $(textarea).summernote({
                        height: 200,
                        minHeight: 150,
                        maxHeight: 400,
                        focus: true,
                        placeholder: $(textarea).attr('placeholder') || 'Введите текст здесь...',
                        disableDragAndDrop: false,
                        callbacks: {
                            onChange: function(contents, $editable) {
                                // Trigger Livewire update
                                $(textarea).trigger('input');
                            },
                            onInit: function() {
                                // Mark as initialized
                                textarea.classList.add('summernote-initialized');
                            }
                        }
                    });
                }
            });
        });

        // Reinitialize when Livewire components are updated
        document.addEventListener('livewire:updated', function() {
            const textareas = document.querySelectorAll('textarea[data-summernote]:not(.summernote-initialized)');

            textareas.forEach(function(textarea) {
                $(textarea).summernote({
                    height: 200,
                    minHeight: 150,
                    maxHeight: 400,
                    focus: true,
                    placeholder: $(textarea).attr('placeholder') || 'Введите текст здесь...',
                    disableDragAndDrop: false,
                    callbacks: {
                        onChange: function(contents, $editable) {
                            $(textarea).trigger('input');
                        },
                        onInit: function() {
                            textarea.classList.add('summernote-initialized');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
