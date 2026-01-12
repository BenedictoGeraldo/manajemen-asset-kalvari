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
        <!-- Sidebar -->
        <aside id="sidebar"
               :class="sidebarMinimized ? 'w-20' : 'w-64'"
               class="bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition md:translate-x-0">

            <!-- Logo Section -->
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-3 overflow-hidden">
                    <div class="w-10 h-10 flex items-center justify-center flex-shrink-0">
                        <img src="{{ asset('logo-pelita-cross.png') }}" alt="PELITA Logo" class="w-15 h-15 object-contain">
                    </div>
                    <h1 x-show="!sidebarMinimized"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-90"
                        class="text-xl font-bold text-gray-800 whitespace-nowrap">PELITA</h1>
                </div>
                <button @click="sidebarMinimized = !sidebarMinimized"
                        class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors duration-150 flex-shrink-0">
                    <svg x-show="!sidebarMinimized" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                    <svg x-show="sidebarMinimized" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Menu Items with Scrollable Area -->
            <nav class="flex-1 overflow-y-auto py-4">
                <div class="px-3 space-y-1">
                    <!-- Dashboard -->
                    @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('dashboard.view'))
                    <a href="{{ route('dashboard') }}" data-navigate data-route="dashboard"
                       :class="sidebarMinimized ? 'justify-center' : ''"
                       class="nav-link flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}"
                       :title="sidebarMinimized ? 'Dashboard' : ''">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span x-show="!sidebarMinimized"
                              x-transition:enter="transition ease-out duration-200"
                              x-transition:enter-start="opacity-0"
                              x-transition:enter-end="opacity-100"
                              x-transition:leave="transition ease-in duration-150"
                              x-transition:leave-start="opacity-100"
                              x-transition:leave-end="opacity-0"
                              class="ml-3 whitespace-nowrap">Dashboard</span>
                    </a>
                    @endif

                    <!-- Data Master Dropdown -->
                    @if(auth()->user()->is_super_admin || auth()->user()->hasAnyPermission(['data-aset.view', 'master.kategori.view', 'master.lokasi.view', 'master.kondisi.view', 'master.pengelola.view']))
                    <div class="relative" x-data="{ open: {{ request()->routeIs('master.*') || request()->routeIs('data-aset.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                                :class="sidebarMinimized ? 'justify-center' : 'justify-between'"
                                :title="sidebarMinimized ? 'Data Master' : ''"
                                class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 text-gray-700 hover:bg-gray-100">
                            <div class="flex items-center" :class="sidebarMinimized ? '' : 'space-x-3'">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                </svg>
                                <span x-show="!sidebarMinimized"
                                      x-transition:enter="transition ease-out duration-200"
                                      x-transition:enter-start="opacity-0"
                                      x-transition:enter-end="opacity-100"
                                      x-transition:leave="transition ease-in duration-150"
                                      x-transition:leave-start="opacity-100"
                                      x-transition:leave-end="opacity-0"
                                      class="whitespace-nowrap">Data Master</span>
                            </div>
                            <svg x-show="!sidebarMinimized" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open && !sidebarMinimized"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="mt-1 ml-4 space-y-1">

                            <!-- Data Aset -->
                            @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('data-aset.view'))
                            <a href="{{ route('data-aset.index') }}" data-navigate data-route="data-aset"
                               class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('data-aset.*') ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="whitespace-nowrap">Data Aset</span>
                            </a>
                            @endif

                            <!-- Master Kategori -->
                            @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('master.kategori.view'))
                            <a href="{{ route('master.kategori.index') }}" data-navigate data-route="master.kategori"
                               class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('master.kategori.*') ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <span class="whitespace-nowrap">Kategori</span>
                            </a>
                            @endif

                            <!-- Master Lokasi -->
                            @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('master.lokasi.view'))
                            <a href="{{ route('master.lokasi.index') }}" data-navigate data-route="master.lokasi"
                               class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('master.lokasi.*') ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="whitespace-nowrap">Lokasi</span>
                            </a>
                            @endif

                            <!-- Master Kondisi -->
                            @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('master.kondisi.view'))
                            <a href="{{ route('master.kondisi.index') }}" data-navigate data-route="master.kondisi"
                               class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('master.kondisi.*') ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="whitespace-nowrap">Kondisi</span>
                            </a>
                            @endif

                            <!-- Master Pengelola -->
                            @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('master.pengelola.view'))
                            <a href="{{ route('master.pengelola.index') }}" data-navigate data-route="master.pengelola"
                               class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('master.pengelola.*') ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="whitespace-nowrap">Pengelola</span>
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Data Transaksional Dropdown -->
                    <div class="relative" x-data="{ open: {{ request()->routeIs('transaksi.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                                :class="sidebarMinimized ? 'justify-center' : 'justify-between'"
                                :title="sidebarMinimized ? 'Data Transaksional' : ''"
                                class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 text-gray-700 hover:bg-gray-100">
                            <div class="flex items-center" :class="sidebarMinimized ? '' : 'space-x-3'">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                <span x-show="!sidebarMinimized"
                                      x-transition:enter="transition ease-out duration-200"
                                      x-transition:enter-start="opacity-0"
                                      x-transition:enter-end="opacity-100"
                                      x-transition:leave="transition ease-in duration-150"
                                      x-transition:leave-start="opacity-100"
                                      x-transition:leave-end="opacity-0"
                                      class="whitespace-nowrap">Data Transaksional</span>
                            </div>
                            <svg x-show="!sidebarMinimized" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open && !sidebarMinimized"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="mt-1 ml-4 space-y-1">

                            <!-- Peminjaman Aset -->
                            <a href="#" data-navigate
                               class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 text-gray-600 hover:bg-gray-100">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                                <span class="whitespace-nowrap">Peminjaman Aset</span>
                            </a>

                            <!-- Pemeliharaan -->
                            <a href="#" data-navigate
                               class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 text-gray-600 hover:bg-gray-100">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="whitespace-nowrap">Pemeliharaan</span>
                            </a>

                            <!-- Mutasi Aset -->
                            <a href="#" data-navigate
                               class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 text-gray-600 hover:bg-gray-100">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                                <span class="whitespace-nowrap">Mutasi Aset</span>
                            </a>

                            <!-- Penghapusan Aset -->
                            <a href="#" data-navigate
                               class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 text-gray-600 hover:bg-gray-100">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span class="whitespace-nowrap">Penghapusan Aset</span>
                            </a>
                        </div>
                    </div>

                    <!-- Manajemen User (Super Admin Only) -->
                    @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('user-management.view'))
                    <a href="{{ route('user-management.index') }}" data-navigate data-route="user-management"
                       :class="sidebarMinimized ? 'justify-center' : ''"
                       :title="sidebarMinimized ? 'Manajemen User' : ''"
                       class="nav-link flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('user-management.*') ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span x-show="!sidebarMinimized"
                              x-transition:enter="transition ease-out duration-200"
                              x-transition:enter-start="opacity-0"
                              x-transition:enter-end="opacity-100"
                              x-transition:leave="transition ease-in duration-150"
                              x-transition:leave-start="opacity-100"
                              x-transition:leave-end="opacity-0"
                              class="ml-3 whitespace-nowrap">Manajemen User</span>
                    </a>
                    @endif
                </div>
            </nav>

            <!-- Logout Button -->
            <div class="p-4 border-t border-gray-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            :class="sidebarMinimized ? 'justify-center' : ''"
                            :title="sidebarMinimized ? 'Keluar Akun' : ''"
                            class="w-full flex items-center px-4 py-3 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition-colors duration-150">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span x-show="!sidebarMinimized"
                              x-transition:enter="transition ease-out duration-200"
                              x-transition:enter-start="opacity-0"
                              x-transition:enter-end="opacity-100"
                              x-transition:leave="transition ease-in duration-150"
                              x-transition:leave-start="opacity-100"
                              x-transition:leave-end="opacity-0"
                              class="ml-3 whitespace-nowrap">Keluar Akun</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 hidden md:hidden"></div>

        <!-- Mobile Sidebar Toggle -->
        <button id="mobile-sidebar-toggle" class="md:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg">
            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Main Content -->
        <main class="flex-1 ml-0 transition-all duration-300 ease-in-out overflow-y-auto bg-gray-50"
              :class="sidebarMinimized ? 'md:ml-20' : 'md:ml-64'">
            <!-- Top Bar -->
            <div class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800 ml-12 md:ml-0">@yield('page-title', 'Dashboard')</h2>
                    <div class="flex items-center space-x-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name ?? 'Administrator' }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email ?? '' }}</p>
                        </div>
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                            <span class="text-sm font-semibold text-gray-700">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div id="main-content" class="p-6">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // SPA Navigation System with Persistent Sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const mainContent = document.getElementById('main-content');
            const pageTitle = document.querySelector('main .bg-white h2');

            // Function to update active state based on URL
            function updateActiveState(currentUrl) {
                const navLinks = document.querySelectorAll('a[data-navigate]');
                navLinks.forEach(link => {
                    const linkHref = link.getAttribute('href');
                    const linkRoute = link.getAttribute('data-route');
                    const isSubmenu = link.classList.contains('submenu-link');

                    // Reset all links
                    link.classList.remove('bg-blue-500', 'text-white');
                    if (isSubmenu) {
                        link.classList.add('text-gray-600');
                    } else {
                        link.classList.add('text-gray-700');
                    }
                    link.classList.add('hover:bg-gray-100');

                    // Check if current link should be active
                    if (linkRoute) {
                        // For routes like data-aset or master.kategori
                        if (currentUrl.includes('/' + linkRoute.replace('.', '/'))) {
                            link.classList.remove('text-gray-700', 'text-gray-600', 'hover:bg-gray-100');
                            link.classList.add('bg-blue-500', 'text-white');
                        }
                    } else if (linkHref === currentUrl || currentUrl.startsWith(linkHref + '/')) {
                        link.classList.remove('text-gray-700', 'text-gray-600', 'hover:bg-gray-100');
                        link.classList.add('bg-blue-500', 'text-white');
                    }
                });
            }

            // Function to attach navigation handlers
            function attachNavigationHandlers() {
                const navLinks = document.querySelectorAll('a[data-navigate]');

                navLinks.forEach(link => {
                    // Remove existing listener if any
                    link.removeEventListener('click', handleNavigation);
                    // Add new listener
                    link.addEventListener('click', handleNavigation);
                });
            }

            // Navigation handler function
            async function handleNavigation(e) {
                e.preventDefault();
                const url = this.getAttribute('href');

                // Don't navigate if already on the same page
                if (window.location.href === url || window.location.pathname === new URL(url, window.location.origin).pathname) {
                    return;
                }

                // Update active state
                updateActiveState(url);

                // Add loading state
                mainContent.classList.add('page-loading');

                try {
                    // Fetch new content
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Extract content
                    const newContent = doc.querySelector('#main-content');
                    const newTitle = doc.querySelector('main .bg-white h2');
                    const newPageTitle = doc.querySelector('title');

                    // Update content with fade
                    if (newContent) {
                        mainContent.innerHTML = newContent.innerHTML;
                        mainContent.classList.remove('page-loading');
                        mainContent.classList.add('content-fade-in');
                        setTimeout(() => mainContent.classList.remove('content-fade-in'), 300);

                        // Re-attach navigation handlers to new content links
                        attachNavigationHandlers();
                    }

                    // Update page title
                    if (newTitle && pageTitle) {
                        pageTitle.textContent = newTitle.textContent;
                    }

                    if (newPageTitle) {
                        document.title = newPageTitle.textContent;
                    }

                    // Update URL and save state
                    window.history.pushState({ url: url }, '', url);

                    // Update active state after navigation
                    updateActiveState(url);

                    // Reinitialize scripts if needed (for DataTables, etc)
                    const scripts = mainContent.querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        if (script.src) {
                            newScript.src = script.src;
                        } else {
                            newScript.textContent = script.textContent;
                        }
                        document.body.appendChild(newScript);
                        setTimeout(() => newScript.remove(), 100);
                    });

                } catch (error) {
                    console.error('Navigation error:', error);
                    mainContent.classList.remove('page-loading');
                    // Fallback to normal navigation
                    window.location.href = url;
                }
            }

            // Initialize navigation handlers
            attachNavigationHandlers();

            // Handle browser back/forward without reloading
            window.addEventListener('popstate', async function(event) {
                const url = window.location.href;

                // Add loading state
                mainContent.classList.add('page-loading');

                try {
                    // Fetch content for the URL
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Extract content
                    const newContent = doc.querySelector('#main-content');
                    const newTitle = doc.querySelector('main .bg-white h2');
                    const newPageTitle = doc.querySelector('title');

                    // Update content
                    if (newContent) {
                        mainContent.innerHTML = newContent.innerHTML;
                        mainContent.classList.remove('page-loading');
                        mainContent.classList.add('content-fade-in');
                        setTimeout(() => mainContent.classList.remove('content-fade-in'), 300);

                        // Re-attach navigation handlers
                        attachNavigationHandlers();
                    }

                    // Update titles
                    if (newTitle && pageTitle) {
                        pageTitle.textContent = newTitle.textContent;
                    }

                    if (newPageTitle) {
                        document.title = newPageTitle.textContent;
                    }

                    // Update active state
                    updateActiveState(url);

                } catch (error) {
                    console.error('Popstate navigation error:', error);
                    mainContent.classList.remove('page-loading');
                    // Reload as fallback
                    location.reload();
                }
            });
        });

        // Mobile sidebar toggle
        const sidebarToggle = document.getElementById('mobile-sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('mobile-open');
                sidebarOverlay.classList.toggle('hidden');
            });

            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-open');
                sidebarOverlay.classList.add('hidden');
            });
        }
    </script>

</body>
</html>

