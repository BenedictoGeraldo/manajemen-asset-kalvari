@extends('layouts.main')

@section('title', 'Detail Aset - ' . $aset->nama_aset)
@section('page-title', 'Detail Aset')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $aset->nama_aset }}</h3>
            <p class="text-sm text-gray-600 mt-1">Kode: {{ $aset->kode_aset }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('data-aset.edit', $aset->id) }}" data-navigate
               class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            <a href="{{ route('data-aset.index') }}" data-navigate
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Detail Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informasi Dasar -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Dasar</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Kode Aset</label>
                        <p class="mt-1 text-gray-900">{{ $aset->kode_aset }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nama Aset</label>
                        <p class="mt-1 text-gray-900">{{ $aset->nama_aset }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Kategori</label>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $aset->kategori->nama_kategori }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tahun Pengadaan</label>
                        <p class="mt-1 text-gray-900">{{ $aset->tahun_pengadaan }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500">Deskripsi Aset</label>
                        <p class="mt-1 text-gray-900">{{ $aset->deskripsi_aset ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Ukuran & Bentuk -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Ukuran & Bentuk</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Ukuran</label>
                        <p class="mt-1 text-gray-900">{{ $aset->ukuran ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Deskripsi Ukuran/Bentuk</label>
                        <p class="mt-1 text-gray-900">{{ $aset->deskripsi_ukuran_bentuk ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Lokasi & Kegunaan -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Lokasi & Kegunaan</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Lokasi</label>
                        <p class="mt-1 text-gray-900">{{ $aset->lokasi->nama_lokasi }}</p>
                        <p class="text-sm text-gray-500">{{ $aset->lokasi->lokasi_lengkap }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Kondisi</label>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $aset->kondisi->kode_warna }}-100 text-{{ $aset->kondisi->kode_warna }}-800">
                                {{ $aset->kondisi->nama_kondisi }}
                            </span>
                        </p>
                        <p class="text-sm text-gray-500 mt-1">{{ $aset->kondisi->keterangan }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500">Kegunaan</label>
                        <p class="mt-1 text-gray-900">{{ $aset->kegunaan }}</p>
                        @if($aset->keterangan_kegunaan)
                            <p class="text-sm text-gray-500 mt-1">{{ $aset->keterangan_kegunaan }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Jumlah & Tipe -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Jumlah & Tipe</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Jumlah Barang</label>
                        <p class="mt-1 text-2xl font-bold text-gray-900">{{ $aset->jumlah_barang }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tipe Grup</label>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($aset->tipe_grup) }}
                            </span>
                        </p>
                        @if($aset->keterangan_tipe_grup)
                            <p class="text-sm text-gray-500 mt-1">{{ $aset->keterangan_tipe_grup }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Anggaran & Nilai -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Anggaran & Nilai Pengadaan</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Budget</label>
                        <p class="mt-1 text-gray-900">{{ $aset->budget ? 'Rp ' . number_format($aset->budget, 0, ',', '.') : '-' }}</p>
                        @if($aset->keterangan_budget)
                            <p class="text-sm text-gray-500 mt-1">{{ $aset->keterangan_budget }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nilai Pengadaan Total</label>
                        <p class="mt-1 text-lg font-semibold text-green-600">{{ $aset->nilai_total_formatted }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nilai Per Unit</label>
                        <p class="mt-1 text-gray-900">{{ $aset->nilai_per_unit_formatted }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Pengelola -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Pengelola Aset</h4>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nama</label>
                        <p class="mt-1 text-gray-900 font-medium">{{ $aset->pengelola->nama_pengelola }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Jabatan</label>
                        <p class="mt-1 text-gray-900">{{ $aset->pengelola->jabatan }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Departemen</label>
                        <p class="mt-1 text-gray-900">{{ $aset->pengelola->departemen }}</p>
                    </div>
                    @if($aset->pengelola->kontak)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Kontak</label>
                            <p class="mt-1 text-gray-900">{{ $aset->pengelola->kontak }}</p>
                        </div>
                    @endif
                    @if($aset->pengelola->email)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-gray-900">{{ $aset->pengelola->email }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
