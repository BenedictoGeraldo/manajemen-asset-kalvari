@extends('layouts.main')

@section('title', 'Dashboard - PELITA')

@section('content')
<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">

    <!-- Welcome Header -->
    <div class="mb-8 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-2xl p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">Dashboard PELITA</h1>
                <p class="text-purple-100 text-lg">Sistem Manajemen Aset Gereja Kalvari Lubang Buaya</p>
                <p class="text-purple-200 text-sm mt-2">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
            <div class="hidden md:block">
                <img src="{{ asset('logo-pelita.png') }}" alt="Logo PELITA" class="w-24 h-24 opacity-40">
            </div>
        </div>
    </div>

    <!-- Statistics Cards Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-8">

        <!-- Kartu Total Aset -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Total Aset</p>
                    <p class="text-4xl font-bold text-gray-900">{{ $totalAset ?? '0' }}</p>
                    <p class="text-xs text-gray-500 mt-2">Seluruh aset terdaftar</p>
                </div>
                <div class="flex-shrink-0 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-2xl p-4 shadow-lg">
                    <svg class="h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Kartu Total Nilai Aset -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Total Nilai</p>
                    <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($totalNilai ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-2">Nilai keseluruhan aset</p>
                </div>
                <div class="flex-shrink-0 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl p-4 shadow-lg">
                    <svg class="h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Kartu Aset Kondisi Baik -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Kondisi Baik</p>
                    <p class="text-4xl font-bold text-gray-900">{{ $asetBaik ?? '0' }}</p>
                    <p class="text-xs text-gray-500 mt-2">Aset dalam kondisi baik</p>
                </div>
                <div class="flex-shrink-0 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl p-4 shadow-lg">
                    <svg class="h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Kartu Aset Perlu Perhatian -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Perlu Perhatian</p>
                    <p class="text-4xl font-bold text-gray-900">{{ $asetPerluPerbaikan ?? '0' }}</p>
                    <p class="text-xs text-gray-500 mt-2">Memerlukan perbaikan</p>
                </div>
                <div class="flex-shrink-0 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl p-4 shadow-lg">
                    <svg class="h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Kartu Jumlah Kategori -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Kategori</p>
                    <p class="text-4xl font-bold text-gray-900">{{ $totalKategori ?? '0' }}</p>
                    <p class="text-xs text-gray-500 mt-2">Jenis kategori aset</p>
                </div>
                <div class="flex-shrink-0 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl p-4 shadow-lg">
                    <svg class="h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Kartu Aset Terbaru -->
        <div class="bg-white overflow-hidden shadow-xl rounded-2xl p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl border-l-4 border-pink-500">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Aset Terbaru</p>
                    <p class="text-4xl font-bold text-gray-900">{{ $asetTerbaruTahun ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500 mt-2">Tahun perolehan terbaru</p>
                </div>
                <div class="flex-shrink-0 bg-gradient-to-br from-pink-400 to-pink-600 rounded-2xl p-4 shadow-lg">
                    <svg class="h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-xl p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            Aksi Cepat
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('data-aset.index') }}" class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl hover:from-purple-100 hover:to-indigo-100 transition-all duration-200 border-2 border-transparent hover:border-purple-300">
                <div class="bg-purple-600 rounded-lg p-3 mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Kelola Data Aset</p>
                    <p class="text-sm text-gray-600">Tambah, edit, atau hapus data aset</p>
                </div>
            </a>
            <a href="{{ route('data-aset.index') }}" class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-all duration-200 border-2 border-transparent hover:border-green-300">
                <div class="bg-green-600 rounded-lg p-3 mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Lihat Laporan</p>
                    <p class="text-sm text-gray-600">Analisis dan statistik aset</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

