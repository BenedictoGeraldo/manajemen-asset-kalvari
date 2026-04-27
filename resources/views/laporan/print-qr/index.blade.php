@extends('layouts.main')

@section('title', 'Cetak QR Code Aset')
@section('page-title', 'Cetak QR Code Aset')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h3 class="text-xl font-bold text-gray-800">Filter Cetak QR Code</h3>
                <p class="text-sm text-gray-600 mt-1">Pilih filter di bawah ini untuk menghasilkan label QR Code aset secara massal.</p>
            </div>
            
            <form action="{{ route('laporan.print-qr.generate') }}" method="GET" target="_blank" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Departemen -->
                    <div>
                        <label for="department_id" class="block text-sm font-semibold text-gray-700 mb-2">Departemen / Seksi</label>
                        <select name="department_id" id="department_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">-- Semua Departemen --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label for="lokasi_id" class="block text-sm font-semibold text-gray-700 mb-2">Lokasi Aset</label>
                        <select name="lokasi_id" id="lokasi_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">-- Semua Lokasi --</option>
                            @foreach($lokasis as $lokasi)
                                <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }} {{ $lokasi->sub_lokasi ? '(' . $lokasi->sub_lokasi . ')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="kategori_id" class="block text-sm font-semibold text-gray-700 mb-2">Kategori Aset</label>
                        <select name="kategori_id" id="kategori_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">-- Semua Kategori --</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pengelola (Optional) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 invisible">Placeholder</label>
                        <div class="flex items-center space-x-2 text-sm text-gray-500 italic mt-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>QR Code akan digenerate berdasarkan pilihan di atas.</span>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition duration-300 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Generate QR Code Printer
                    </button>
                    <p class="text-xs text-center text-gray-500">Akan membuka tab baru untuk tampilan cetak.</p>
                </div>
            </form>
        </div>

        <!-- Info Section -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 flex items-start space-x-3">
                <div class="bg-blue-100 p-2 rounded text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-blue-800">Format Label</h4>
                    <p class="text-xs text-blue-600 mt-1">Tampilan cetak dioptimalkan untuk kertas A4 dengan grid 3x10 atau disesuaikan melalui browser settings.</p>
                </div>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-100 flex items-start space-x-3">
                <div class="bg-green-100 p-2 rounded text-green-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-green-800">QR Code Presisi</h4>
                    <p class="text-xs text-green-600 mt-1">QR Code mengandung Kode Aset 15 karakter yang unik untuk memudahkan tracking via scanner.</p>
                </div>
            </div>
            <div class="bg-amber-50 p-4 rounded-lg border border-amber-100 flex items-start space-x-3">
                <div class="bg-amber-100 p-2 rounded text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-amber-800">Tips Cetak</h4>
                    <p class="text-xs text-amber-600 mt-1">Pastikan "Background Graphics" dicentang pada jendela print browser untuk tampilan terbaik.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
