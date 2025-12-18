@extends('layouts.main')

@section('title', 'Dashboard - PELITA')

@section('content')
<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">

    <!-- Welcome Header -->
    <div class="mb-8 bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-600">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-1">Dashboard PELITA</h1>
                <p class="text-gray-600 text-sm">Sistem Manajemen Aset Gereja Kalvari Lubang Buaya</p>
                <p class="text-gray-500 text-xs mt-1">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

        <!-- Kartu Total Aset -->
        <div class="bg-white overflow-hidden shadow-md rounded-lg p-5 border-l-4 border-purple-600 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Aset</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalAset ?? '0' }}</p>
                    <p class="text-xs text-gray-500 mt-1">Seluruh aset terdaftar</p>
                </div>
                <div class="flex-shrink-0 bg-purple-600 rounded-lg p-3">
                    <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Kartu Total Nilai Aset -->
        <div class="bg-white overflow-hidden shadow-md rounded-lg p-5 border-l-4 border-gray-600 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Nilai</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalNilai ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Nilai keseluruhan aset</p>
                </div>
                <div class="flex-shrink-0 bg-gray-600 rounded-lg p-3">
                    <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Kartu Aset Kondisi Baik -->
        <div class="bg-white overflow-hidden shadow-md rounded-lg p-5 border-l-4 border-green-600 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Kondisi Baik</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $asetBaik ?? '0' }}</p>
                    <p class="text-xs text-gray-500 mt-1">Aset dalam kondisi baik</p>
                </div>
                <div class="flex-shrink-0 bg-green-600 rounded-lg p-3">
                    <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Kartu Aset Perlu Perhatian -->
        <div class="bg-white overflow-hidden shadow-md rounded-lg p-5 border-l-4 border-yellow-600 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Perlu Perhatian</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $asetPerluPerbaikan ?? '0' }}</p>
                    <p class="text-xs text-gray-500 mt-1">Memerlukan perbaikan</p>
                </div>
                <div class="flex-shrink-0 bg-yellow-600 rounded-lg p-3">
                    <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Kartu Jumlah Kategori -->
        <div class="bg-white overflow-hidden shadow-md rounded-lg p-5 border-l-4 border-indigo-600 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Kategori</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalKategori ?? '0' }}</p>
                    <p class="text-xs text-gray-500 mt-1">Jenis kategori aset</p>
                </div>
                <div class="flex-shrink-0 bg-indigo-600 rounded-lg p-3">
                    <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Kartu Aset Terbaru -->
        <div class="bg-white overflow-hidden shadow-md rounded-lg p-5 border-l-4 border-blue-600 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Aset Terbaru</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $asetTerbaruTahun ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500 mt-1">Tahun perolehan terbaru</p>
                </div>
                <div class="flex-shrink-0 bg-blue-600 rounded-lg p-3">
                    <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

