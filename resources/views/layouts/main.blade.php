<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PELITA - Manajemen Aset')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo-pelita.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
    <style>
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .slide-down {
            animation: slideDown 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-purple-50 to-gray-100 min-h-screen">

    <!-- Enhanced Navbar -->
    <nav class="bg-gradient-to-r from-[#343A40] via-[#3d444a] to-[#343A40] shadow-2xl border-b border-gray-700/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-24">
                <!-- Brand -->
                <div class="flex items-center">
                    <div class="text-white">
                        <h1 class="text-2xl font-bold tracking-wide">PELITA</h1>
                        <p class="text-xs text-gray-300 -mt-1">Pencatatan Elektronik List Terpusat Aset</p>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <div class="hidden md:block">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('dashboard') }}"
                           class="{{ request()->routeIs('dashboard') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <a href="{{ route('data-aset.index') }}"
                           class="{{ request()->routeIs('data-aset.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Data Aset</span>
                        </a>
                    </div>
                </div>

                <!-- User Info and Logout -->
                <div class="hidden md:flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-white text-sm font-semibold">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="text-gray-400 text-xs">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 flex items-center space-x-2 shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-gray-300 hover:text-white p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-gray-800 border-t border-gray-700 slide-down">
            <div class="px-4 py-3 space-y-2">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700' }} block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                <a href="{{ route('data-aset.index') }}" class="{{ request()->routeIs('data-aset.*') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700' }} block px-3 py-2 rounded-md text-base font-medium">Data Aset</a>
                <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-gray-700">
                    @csrf
                    <button type="submit" class="w-full text-left text-red-400 hover:bg-gray-700 px-3 py-2 rounded-md text-base font-medium">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>

</body>
</html>

