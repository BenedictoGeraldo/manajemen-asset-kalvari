@extends('layouts.main')

@section('title', 'Dashboard - PELITA')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-fadeInUp {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .animate-slideIn {
        animation: slideIn 0.6s ease-out forwards;
    }

    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .progress-bar {
        transition: width 1.5s ease-out;
    }
</style>
@endpush

@section('content')
<!-- Welcome Header -->
<div class="mb-6 bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Dashboard PELITA</h1>
    <p class="text-gray-600">Sistem Manajemen Aset Gereja Kalvari Lubang Buaya</p>
    <div class="flex items-center mt-2 text-gray-500 text-sm">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        {{ now()->isoFormat('dddd, D MMMM Y') }}
    </div>
</div>

<!-- Statistics Cards Grid -->
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-6">

    <!-- Kartu Total Aset -->
    <div class="bg-white overflow-hidden shadow rounded-lg p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-blue-50 rounded-lg p-3">
                <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs font-medium text-gray-500 uppercase">Total Aset</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalAset ?? '0' }}</p>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-3">
            <p class="text-sm text-gray-600">Seluruh aset terdaftar</p>
        </div>
    </div>

    <!-- Kartu Total Nilai Aset -->
    <div class="bg-white overflow-hidden shadow rounded-lg p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-green-50 rounded-lg p-3">
                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs font-medium text-gray-500 uppercase">Total Nilai</p>
                <p class="text-xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalNilai ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-3">
            <p class="text-sm text-gray-600">Nilai keseluruhan aset</p>
        </div>
    </div>

    <!-- Kartu Aset Kondisi Baik -->
    <div class="bg-white overflow-hidden shadow rounded-lg p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-green-50 rounded-lg p-3">
                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs font-medium text-gray-500 uppercase">Kondisi Baik</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $asetBaik ?? '0' }}</p>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-3">
            <p class="text-sm text-gray-600">Aset dalam kondisi prima</p>
        </div>
    </div>

    <!-- Kartu Aset Perlu Perhatian -->
    <div class="bg-white overflow-hidden shadow rounded-lg p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-yellow-50 rounded-lg p-3">
                <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs font-medium text-gray-500 uppercase">Perlu Perhatian</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $asetPerluPerbaikan ?? '0' }}</p>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-3">
            <p class="text-sm text-gray-600">Memerlukan perbaikan segera</p>
        </div>
    </div>

    <!-- Kartu Jumlah Kategori -->
    <div class="bg-white overflow-hidden shadow rounded-lg p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-blue-50 rounded-lg p-3">
                <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs font-medium text-gray-500 uppercase">Kategori</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalKategori ?? '0' }}</p>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-3">
            <p class="text-sm text-gray-600">Jenis kategori aset berbeda</p>
        </div>
    </div>

    <!-- Kartu Aset Terbaru -->
    <div class="bg-white overflow-hidden shadow rounded-lg p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-gray-50 rounded-lg p-3">
                <svg class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs font-medium text-gray-500 uppercase">Aset Terbaru</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $asetTerbaruTahun ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-3">
            <p class="text-sm text-gray-600">Tahun perolehan terbaru</p>
        </div>
    </div>
</div>

<!-- Kondisi Aset Overview -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Kondisi Aset Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Distribusi Kondisi Aset
        </h3>

        @php
            $totalAsetForPercentage = $totalAset > 0 ? $totalAset : 1;
            $persentaseBaik = round(($asetBaik / $totalAsetForPercentage) * 100);
            $persentasePerluPerbaikan = round(($asetPerluPerbaikan / $totalAsetForPercentage) * 100);
        @endphp

        <div class="space-y-5">
            <!-- Baik -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700 flex items-center">
                        <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                        Kondisi Baik
                    </span>
                    <span class="text-sm font-bold text-gray-800">{{ $asetBaik }} ({{ $persentaseBaik }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $persentaseBaik }}%"></div>
                </div>
            </div>

            <!-- Perlu Perhatian -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700 flex items-center">
                        <span class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                        Perlu Perhatian
                    </span>
                    <span class="text-sm font-bold text-gray-800">{{ $asetPerluPerbaikan }} ({{ $persentasePerluPerbaikan }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-yellow-500 h-2.5 rounded-full" style="width: {{ $persentasePerluPerbaikan }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Info Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Informasi Cepat
        </h3>

        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">Rata-rata Nilai Aset</span>
                <span class="text-sm font-bold text-blue-600">
                    Rp {{ $totalAset > 0 ? number_format($totalNilai / $totalAset, 0, ',', '.') : '0' }}
                </span>
            </div>

            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">Tingkat Kesehatan Aset</span>
                <span class="text-sm font-bold {{ $persentaseBaik >= 80 ? 'text-green-600' : ($persentaseBaik >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                    {{ $persentaseBaik >= 80 ? 'Sangat Baik' : ($persentaseBaik >= 50 ? 'Cukup Baik' : 'Perlu Perhatian') }}
                </span>
            </div>

            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">Total Kategori</span>
                <span class="text-sm font-bold text-blue-600">{{ $totalKategori }} Kategori</span>
            </div>

            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">Tahun Pengadaan Terbaru</span>
                <span class="text-sm font-bold text-gray-600">{{ $asetTerbaruTahun ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex flex-col md:flex-row items-center justify-between">
        <div class="mb-4 md:mb-0">
            <h3 class="text-lg font-bold text-gray-800 mb-1">Kelola Aset Gereja</h3>
            <p class="text-gray-600 text-sm">Akses penuh untuk menambah, mengedit, atau menghapus data aset</p>
        </div>
        <a href="{{ route('data-aset.index') }}" class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Kelola Data Aset
        </a>
    </div>
</div>
@endsection

