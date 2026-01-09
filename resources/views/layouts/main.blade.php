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
<body class="bg-gray-50 min-h-screen">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-animate md:translate-x-0">
            <!-- Logo Section -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <img src="{{ asset('logo-pelita-cross.png') }}" alt="PELITA Logo" class="w-15 h-15 object-contain">
                    </div>
                    <h1 class="text-xl font-bold text-gray-800">PELITA</h1>
                </div>
            </div>

            <!-- Menu Items -->
            <nav class="flex-1 overflow-y-auto py-4">
                <div class="px-3 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" data-navigate
                       class="nav-link flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Dashboard</span>
                        </div>
                    </a>

                    <!-- Data Aset -->
                    <a href="{{ route('data-aset.index') }}" data-navigate
                       class="nav-link flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('data-aset.*') ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Data Aset</span>
                        </div>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <!-- Data Master Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('master.*') ? 'bg-blue-500 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                <span>Data Master</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="mt-1 ml-4 space-y-1"
                             style="display: none;">

                            <!-- Master Kategori -->
                            <a href="{{ route('master.kategori.index') }}" data-navigate
                               class="nav-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('master.kategori.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <span>Kategori</span>
                            </a>

                            <!-- Master Lokasi -->
                            <a href="{{ route('master.lokasi.index') }}" data-navigate
                               class="nav-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('master.lokasi.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Lokasi</span>
                            </a>

                            <!-- Master Kondisi -->
                            <a href="{{ route('master.kondisi.index') }}" data-navigate
                               class="nav-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('master.kondisi.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Kondisi</span>
                            </a>

                            <!-- Master Pengelola -->
                            <a href="{{ route('master.pengelola.index') }}" data-navigate
                               class="nav-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('master.pengelola.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Pengelola</span>
                            </a>
                        </div>
                    </div>

                    <!-- Pemeliharaan -->
                    <a href="#"
                       class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 text-gray-700 hover:bg-gray-100">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Pemeliharaan</span>
                        </div>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <!-- Laporan -->
                    <a href="#"
                       class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 text-gray-700 hover:bg-gray-100">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Laporan</span>
                        </div>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <!-- Pengaturan -->
                    <a href="#"
                       class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 text-gray-700 hover:bg-gray-100">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            <span>Pengaturan</span>
                        </div>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </nav>

            <!-- Logout Button -->
            <div class="p-4 border-t border-gray-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition-colors duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Keluar Akun</span>
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
        <main class="flex-1 ml-0 md:ml-64 overflow-y-auto bg-gray-50">
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
        // SPA Navigation System
        document.addEventListener('DOMContentLoaded', function() {
            const mainContent = document.getElementById('main-content');
            const pageTitle = document.querySelector('main .bg-white h2');
            const navLinks = document.querySelectorAll('a[data-navigate]');

            // Handle navigation clicks
            navLinks.forEach(link => {
                link.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');

                    // Update active state
                    navLinks.forEach(l => {
                        l.classList.remove('bg-blue-500', 'text-white');
                        l.classList.add('text-gray-700', 'hover:bg-gray-100');
                    });
                    this.classList.remove('text-gray-700', 'hover:bg-gray-100');
                    this.classList.add('bg-blue-500', 'text-white');

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
                        }

                        // Update page title
                        if (newTitle && pageTitle) {
                            pageTitle.textContent = newTitle.textContent;
                        }

                        if (newPageTitle) {
                            document.title = newPageTitle.textContent;
                        }

                        // Update URL
                        window.history.pushState({}, '', url);

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
                });
            });

            // Handle browser back/forward
            window.addEventListener('popstate', function() {
                location.reload();
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

