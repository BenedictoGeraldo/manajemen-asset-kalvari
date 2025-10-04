<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Manajemen Aset')</title> {{-- Memberikan judul default jika tidak di-set di halaman anak --}}
    
    {{-- Memuat Tailwind CSS dari CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div id="app">
        <!-- Navbar -->
        <nav style="background-color: #343A40;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            {{-- Anda bisa mengganti ini dengan logo --}}
                            <a href="{{ route('dashboard') }}" class="text-white font-bold text-xl">Manajemen Aset</a>
                        </div>
                    </div>
                    <div>
                        {{-- Hanya tampilkan tombol logout jika user terautentikasi --}}
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                    Logout
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            {{-- Di sinilah konten dari halaman spesifik (seperti dashboard) akan disisipkan --}}
            @yield('content')
        </main>
    </div>
</body>
</html>
