@extends('layouts.main')

@section('title', 'Laporan Mutasi Aset')
@section('page-title', 'Laporan Mutasi Aset')

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
        <form method="GET" action="{{ route('laporan.mutasi-aset.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ $filters['search'] ?? '' }}"
                           placeholder="Nomor mutasi, kode aset, nama aset..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">Jenis Mutasi</label>
                    <select id="jenis"
                            name="jenis"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Jenis</option>
                        <option value="transfer_lokasi" {{ ($filters['jenis'] ?? '') === 'transfer_lokasi' ? 'selected' : '' }}>Transfer Lokasi</option>
                        <option value="perubahan_kondisi" {{ ($filters['jenis'] ?? '') === 'perubahan_kondisi' ? 'selected' : '' }}>Perubahan Kondisi</option>
                        <option value="penghapusan" {{ ($filters['jenis'] ?? '') === 'penghapusan' ? 'selected' : '' }}>Penghapusan</option>
                        <option value="write_off" {{ ($filters['jenis'] ?? '') === 'write_off' ? 'selected' : '' }}>Write Off</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status"
                            name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ ($filters['status'] ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="diajukan" {{ ($filters['status'] ?? '') === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="disetujui" {{ ($filters['status'] ?? '') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ ($filters['status'] ?? '') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="proses" {{ ($filters['status'] ?? '') === 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ ($filters['status'] ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ ($filters['status'] ?? '') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
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
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                    Terapkan Filter
                </button>
                <a href="{{ route('laporan.mutasi-aset.index') }}"
                   data-navigate
                   class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-lg transition-colors duration-150">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="flex justify-end mb-4">
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                    @click.away="open = false"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
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
                    <a href="{{ route('laporan.mutasi-aset.export', array_merge(['format' => 'xlsx'], request()->except('page'))) }}"
                       class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                        Export ke Excel (.xlsx)
                    </a>
                    <a href="{{ route('laporan.mutasi-aset.export', array_merge(['format' => 'csv'], request()->except('page'))) }}"
                       class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Mutasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aset</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi Lama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi Baru</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($laporanMutasi as $mutasi)
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-700',
                                'diajukan' => 'bg-yellow-100 text-yellow-800',
                                'disetujui' => 'bg-blue-100 text-blue-800',
                                'ditolak' => 'bg-red-100 text-red-700',
                                'proses' => 'bg-indigo-100 text-indigo-800',
                                'selesai' => 'bg-green-100 text-green-800',
                                'dibatalkan' => 'bg-gray-200 text-gray-700',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $mutasi->nomor_mutasi }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $mutasi->tanggal_mutasi?->format('d/m/Y') ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="font-medium text-gray-900">{{ $mutasi->aset?->nama_aset ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $mutasi->aset?->kode_aset ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $mutasi->jenis_mutasi)) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$mutasi->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($mutasi->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $mutasi->lokasiLama?->nama_lokasi ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $mutasi->lokasiBaru?->nama_lokasi ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-6 text-center text-sm text-gray-500">
                                Tidak ada data mutasi aset yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $laporanMutasi->links() }}
        </div>
    </div>
</div>
@endsection
