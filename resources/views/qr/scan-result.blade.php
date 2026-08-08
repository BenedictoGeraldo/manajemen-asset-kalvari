<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Aset - {{ $aset->nama_aset }}</title>
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
                    {{ setting('church_name', 'Gereja Kalvari') }}
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
                        <p class="text-sm font-semibold text-gray-800">{{ $aset->lokasi?->nama_lokasi ?? 'Tidak diketahui' }}</p>
                    </div>
                </div>

                @auth
                {{-- Detail tambahan hanya untuk user login --}}
                @if($aset->kondisi)
                <div class="flex items-center p-3 bg-white/60 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center shrink-0 mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-400">Kondisi</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $aset->kondisi->nama_kondisi }}</p>
                    </div>
                </div>
                @endif

                <div class="flex items-center p-3 bg-white/60 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center shrink-0 mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-400">Pengelola</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $aset->pengelola?->nama_pengelola ?? 'Tidak diketahui' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="flex items-center p-3 bg-white/60 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="w-8 h-8 rounded-lg bg-cyan-100 text-cyan-600 flex items-center justify-center shrink-0 mr-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider font-bold text-gray-400">Jumlah</p>
                            <p class="text-sm font-semibold text-gray-800">{{ number_format((int) ($aset->jumlah_barang ?? 0), 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-white/60 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 mr-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider font-bold text-gray-400">Tahun</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $aset->tahun_pengadaan ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                @endauth

            </div>

            <div class="mt-8 text-center space-y-3">
                <p class="text-xs text-gray-400">{{ setting('church_name', 'Gereja Kalvari') }}</p>

                @auth
                <a href="{{ route('data-aset.show', $aset->id) }}" data-navigate class="inline-flex items-center justify-center px-6 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-full hover:bg-gray-800 transition-colors shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Lihat Detail
                </a>
                @else
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-full hover:bg-gray-800 transition-colors shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Login untuk Info Lengkap
                </a>
                @endauth
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
