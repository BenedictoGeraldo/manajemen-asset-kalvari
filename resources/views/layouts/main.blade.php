<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Manajemen Aset')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <nav class="bg-[#343A40] shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Item Kiri: Judul Aplikasi -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <!-- Ganti dengan logo jika ada -->
                        <span class="text-white font-bold text-xl">Pelita</span>
                    </div>
                </div>
                
                <!-- Item Tengah: Menu Navigasi -->
                <div class="hidden md:block">
                    <div class="flex items-baseline space-x-4">
                        <!-- Menu Navigasi -->
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                        
                        {{-- Anda perlu membuat route dengan nama 'aset.index' nantinya --}}
                        <a href="#" class="{{ request()->routeIs('aset.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 rounded-md text-sm font-medium">Data Aset</a>
                    </div>
                </div>
                
                <!-- Item Kanan: Tombol Logout -->
                <div class="hidden md:block">
                    <!-- Form Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

</body>
</html>

