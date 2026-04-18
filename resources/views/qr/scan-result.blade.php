<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Aset - {{ $aset->nama_aset }}</title>
    <!-- Menggunakan CDN agar styling langsung jalan di HP tanpa perlu setting server Vite -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4 antialiased">

    <div class="max-w-sm w-full glass-panel rounded-3xl overflow-hidden relative">
        <!-- Header / Banner -->
        <div class="h-32 bg-gradient-to-br from-indigo-600 via-blue-600 to-sky-500 relative">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
            <div class="absolute bottom-4 left-6 right-6">
                <span class="inline-block px-3 py-1 mb-2 bg-white/20 backdrop-blur-md border border-white/30 text-white text-xs font-bold tracking-wide uppercase rounded-full shadow-sm">
                    Aset Gereja Kalvari
                </span>
            </div>
        </div>

        <div class="px-6 pb-8 -mt-8 relative z-10">
            <!-- Image Profile (Asset Picture) -->
            <div class="flex justify-center mb-5">
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    @if($aset->gambar_aset_base64)
                        <img src="{{ $aset->gambar_aset_base64 }}" alt="{{ $aset->nama_aset }}" 
                             class="relative w-40 h-40 object-cover rounded-2xl shadow-lg border-4 border-white bg-white cursor-pointer transition-transform hover:scale-105"
                             onclick="openImageModal(this.src)" title="Klik untuk memperbesar">
                    @else
                        <div class="relative w-40 h-40 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300 shadow-lg border-4 border-white">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Asset Info -->
            <div class="text-center mb-6">
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight leading-tight mb-1">{{ $aset->nama_aset }}</h1>
                <p class="text-sm font-medium text-gray-500">{{ $aset->kode_aset ?? 'Tanpa Kode' }}</p>
            </div>

            <!-- Details List -->
            <div class="space-y-3">
                
                @if($aset->kategori)
                <div class="flex items-center p-3 bg-white/60 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center shrink-0 mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-400">Kategori</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $aset->kategori->nama_kategori }}</p>
                    </div>
                </div>
                @endif

                <div class="flex items-center p-3 bg-white/60 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-400">Lokasi Penempatan</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $aset->lokasi ? $aset->lokasi->nama_lokasi : 'Tidak diketahui' }}</p>
                    </div>
                </div>

                <div class="flex items-center p-3 bg-white/60 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-400">Status Aset</p>
                        <p class="text-sm font-semibold text-gray-800">Tercatat di Sistem</p>
                    </div>
                </div>

            </div>
            
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-400 mb-2">Internal Gereja Kalvari</p>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-full hover:bg-gray-800 transition-colors shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Login Sistem
                </a>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/95 p-4 transition-opacity" onclick="closeImageModal()">
        <img id="modalImage" src="" class="max-w-full max-h-full rounded-lg object-contain shadow-2xl">
        <button class="absolute top-6 right-6 text-white bg-white/10 hover:bg-white/20 p-2 rounded-full backdrop-blur-md transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <script>
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            const modal = document.getElementById('imageModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</body>
</html>
