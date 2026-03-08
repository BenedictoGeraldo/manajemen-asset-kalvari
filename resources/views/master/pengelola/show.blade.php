@extends('layouts.main')

@section('title', 'Detail Pengelola - ' . $pengelola->nama_pengelola)
@section('page-title', 'Detail Pengelola')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $pengelola->nama_pengelola }}</h3>
            <p class="text-sm text-gray-600 mt-1">{{ $pengelola->jabatan }} - {{ $pengelola->departemen }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('master.pengelola.edit', $pengelola->id) }}" data-navigate
               class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            <a href="{{ route('master.pengelola.index') }}" data-navigate
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
        <div class="lg:col-span-1">
            <!-- Informasi Pengelola -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Pengelola</h4>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nama Lengkap</label>
                        <p class="mt-1 text-gray-900 font-medium">{{ $pengelola->nama_pengelola }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Jabatan</label>
                        <p class="mt-1 text-gray-900">{{ $pengelola->jabatan }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Departemen</label>
                        <p class="mt-1 text-gray-900">{{ $pengelola->departemen }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="mt-1 text-gray-900">{{ $pengelola->email ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Kontak</label>
                        <p class="mt-1 text-gray-900">{{ $pengelola->kontak ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status</label>
                        <p class="mt-1">
                            @if($pengelola->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Nonaktif
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Total Aset Dikelola</label>
                        <p class="mt-1 text-2xl font-bold text-blue-600">{{ $pengelola->data_aset_count }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assets List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800">Daftar Aset yang Dikelola</h4>
                            <p class="text-sm text-gray-600 mt-1">Total {{ $pengelola->dataAset->count() }} aset</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    @if($pengelola->dataAset->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Aset</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Aset</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Perolehan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pengelola->dataAset as $index => $aset)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $aset->kode_aset }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $aset->nama_aset }}</div>
                                    @if($aset->deskripsi_aset)
                                    <div class="text-sm text-gray-500">{{ Str::limit($aset->deskripsi_aset, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $aset->kategori->nama_kategori }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $aset->lokasi->nama_lokasi }}</div>
                                    @if($aset->lokasi->lokasi_lengkap)
                                    <div class="text-xs text-gray-500">{{ Str::limit($aset->lokasi->lokasi_lengkap, 30) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $aset->kondisi->kode_warna }}-100 text-{{ $aset->kondisi->kode_warna }}-800">
                                        {{ $aset->kondisi->nama_kondisi }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Rp {{ number_format($aset->nilai_perolehan, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('data-aset.show', $aset->id) }}" data-navigate
                                           class="text-blue-600 hover:text-blue-900" title="Lihat Detail Aset">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada aset</h3>
                        <p class="mt-1 text-sm text-gray-500">Pengelola ini belum mengelola aset apapun.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
