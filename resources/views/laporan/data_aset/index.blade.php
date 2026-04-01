@extends('layouts.main')

@section('title', 'Laporan Data Aset')
@section('page-title', 'Laporan Data Aset')

@section('content')
<div class="p-6">
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('laporan.data-aset.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ $filters['search'] ?? '' }}"
                           placeholder="Kode aset, nama aset, kategori, lokasi..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select id="kategori_id"
                            name="kategori_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ (string) ($filters['kategori_id'] ?? '') === (string) $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="lokasi_id" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <select id="lokasi_id"
                            name="lokasi_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Lokasi</option>
                        @foreach($lokasis as $lokasi)
                            <option value="{{ $lokasi->id }}" {{ (string) ($filters['lokasi_id'] ?? '') === (string) $lokasi->id ? 'selected' : '' }}>
                                {{ $lokasi->nama_lokasi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="kondisi_id" class="block text-sm font-medium text-gray-700 mb-1">Kondisi</label>
                    <select id="kondisi_id"
                            name="kondisi_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kondisi</option>
                        @foreach($kondisis as $kondisi)
                            <option value="{{ $kondisi->id }}" {{ (string) ($filters['kondisi_id'] ?? '') === (string) $kondisi->id ? 'selected' : '' }}>
                                {{ $kondisi->nama_kondisi }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Aset</label>
                    <select id="status"
                            name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ ($filters['status'] ?? '') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ ($filters['status'] ?? '') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div>
                    <label for="per_page" class="block text-sm font-medium text-gray-700 mb-1">Data per Halaman</label>
                    <select id="per_page"
                            name="per_page"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ (int) ($filters['per_page'] ?? 10) === $size ? 'selected' : '' }}>
                                {{ $size }} / halaman
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="btn-a-sm">
                        Terapkan Filter
                    </button>
                    <a href="{{ route('laporan.data-aset.index') }}"
                       data-navigate
                       class="btn-c-outline">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="flex justify-end mb-4">
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                    @click.away="open = false"
                    class="btn-export-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Laporan
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open"
                 x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-52 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                <div class="py-1">
                    <a href="{{ route('laporan.data-aset.export', array_merge(['format' => 'xlsx'], request()->except('page'))) }}"
                       class="dropdown-export-item">
                        Export ke Excel (.xlsx)
                    </a>
                    <a href="{{ route('laporan.data-aset.export', array_merge(['format' => 'csv'], request()->except('page'))) }}"
                       class="dropdown-export-item">
                        Export ke CSV (.csv)
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Aset</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Aset</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengelola</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($laporanAset as $aset)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $aset->kode_aset }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $aset->nama_aset }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $aset->kategori?->nama_kategori ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $aset->lokasi?->nama_lokasi ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $aset->kondisi?->nama_kondisi ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $aset->pengelola?->nama_pengelola ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-700">{{ number_format((int) $aset->jumlah_barang, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-700">
                                Rp {{ number_format((float) $aset->nilai_pengadaan_total, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $aset->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $aset->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-6 text-center text-sm text-gray-500">
                                Tidak ada data aset yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $laporanAset->links() }}
        </div>
    </div>
</div>
@endsection

