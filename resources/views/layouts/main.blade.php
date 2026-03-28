<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PELITA - Manajemen Aset')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo-pelita-cross.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }
        .sidebar-animate {
            animation: slideIn 0.3s ease-out;
        }
        .sidebar-transition {
            transition: width 0.3s ease-in-out;
        }
        @media (max-width: 768px) {
            aside {
                transform: translateX(-100%);
                transition: transform 0.3s ease-out;
            }
            aside.mobile-open {
                transform: translateX(0);
            }
        }
        /* Loading indicator */
        .page-loading {
            opacity: 0.5;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .content-fade-in {
            animation: fadeIn 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ sidebarMinimized: false }">

    <div class="flex h-screen overflow-hidden">
        @include('layouts.partials.sidebar')

        <!-- Main Content -->
        <main class="flex-1 ml-0 transition-all duration-300 ease-in-out overflow-y-auto bg-gray-50"
              :class="sidebarMinimized ? 'md:ml-20' : 'md:ml-64'">
            @include('layouts.partials.topbar')

            <!-- Page Content -->
            <div id="main-content" class="p-6">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>

