<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Aset - {{ $aset->nama_aset }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 text-center text-white">
            <h1 class="text-2xl font-bold mb-1">Manajemen Aset</h1>
            <p class="text-blue-100 text-sm">Gereja Kalvari</p>
        </div>

        <!-- Image Content -->
        <div class="p-6">
            <div class="flex justify-center mb-6">
                @if($aset->gambar_aset_base64)
                    <img src="{{ $aset->gambar_aset_base64 }}" alt="{{ $aset->nama_aset }}" class="w-48 h-48 object-cover rounded-xl shadow-md border-4 border-gray-50">
                @else
                    <div class="w-48 h-48 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 shadow-inner border-4 border-gray-50">
                        <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Details -->
            <div class="space-y-4">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-extrabold text-gray-900">{{ $aset->nama_aset }}</h2>
                    <span class="inline-block mt-2 px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-medium border border-gray-200">
                        {{ $aset->kode_aset ?? 'Tanpa Kode' }}
                    </span>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Lokasi Aset</h3>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <p class="text-gray-800 font-medium leading-tight">
                            {{ $aset->lokasi ? $aset->lokasi->nama_lokasi : 'Tidak diketahui' }}
                        </p>
                    </div>
                </div>

                @if($aset->kategori)
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Kategori</h3>
                    <p class="text-gray-800 font-medium">
                        {{ $aset->kategori->nama_kategori }}
                    </p>
                </div>
                @endif
            </div>
            
            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                    Login Sistem Manajemen
                </a>
            </div>
        </div>
    </div>

</body>
</html>
