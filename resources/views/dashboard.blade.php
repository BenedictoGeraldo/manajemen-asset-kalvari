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
                <p class="text-xs font-medium text-gray-500 uppercase">Total Barang</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($totalAset ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-3">
            <p class="text-sm text-gray-600">Jumlah seluruh barang</p>
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
                <p class="text-xs font-medium text-gray-500 uppercase">Total Record</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalRecord ?? 0 }}</p>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-3">
            <p class="text-sm text-gray-600">Jumlah data aset terdaftar</p>
        </div>
    </div>

    <!-- Kartu Jumlah Lokasi -->
    <div class="bg-white overflow-hidden shadow rounded-lg p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-yellow-50 rounded-lg p-3">
                <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs font-medium text-gray-500 uppercase">Lokasi</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalLokasi ?? 0 }}</p>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-3">
            <p class="text-sm text-gray-600">Jumlah lokasi penyimpanan</p>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs font-medium text-gray-500 uppercase">Aset Terbaru</p>
                <p class="text-xl font-bold text-gray-800 mt-1">{{ $asetTerbaru->first()->nama_aset ?? 'Belum ada' }}</p>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-3">
            <p class="text-sm text-gray-600">Data aset terakhir ditambahkan</p>
        </div>
    </div>
</div>

<!-- Satu Chart Utama -->
<div class="mb-6 bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3v18m4-14v10m4-6v6M7 13v4" />
            </svg>
            Grafik penambahan Aset
        </h3>
        <span class="text-xs text-gray-500">Berdasarkan jumlah barang ditambahkan per bulan</span>
    </div>
    <div class="h-80">
        <canvas id="asetTrendChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartEl = document.getElementById('asetTrendChart');
        if (!chartEl) {
            return;
        }

        new Chart(chartEl, {
            type: 'line',
            data: {
                labels: @json($trendLabels ?? []),
                datasets: [{
                    label: 'Jumlah Barang Masuk',
                    data: @json($trendValues ?? []),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.15)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.2)',
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                },
            },
        });
    });
</script>
@endsection

