@extends('layouts.main')

@section('title', 'Laporan Pembelian')
@section('page-title', 'Laporan Pembelian')

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
        <form method="GET" action="{{ route('laporan.pembelian.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ $filters['search'] ?? '' }}"
                           placeholder="Nomor pembelian atau vendor..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                        <option value="dibatalkan" {{ ($filters['status'] ?? '') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

                <div class="md:col-span-2 flex items-end gap-2">
                    <button type="submit"
                            class="btn-a-sm">
                        Terapkan Filter
                    </button>
                    <a href="{{ route('laporan.pembelian.index') }}"
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
                    <a href="{{ route('laporan.pembelian.export', array_merge(['format' => 'xlsx'], request()->except('page'))) }}"
                       class="dropdown-export-item">
                        Export ke Excel (.xlsx)
                    </a>
                    <a href="{{ route('laporan.pembelian.export', array_merge(['format' => 'csv'], request()->except('page'))) }}"
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Pembelian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Item</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Nilai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posting Aset</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($laporanPembelian as $pembelian)
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-700',
                                'diajukan' => 'bg-yellow-100 text-yellow-800',
                                'disetujui' => 'bg-green-100 text-green-800',
                                'dibatalkan' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pembelian->nomor_pembelian }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $pembelian->tanggal_pembelian?->format('d/m/Y') ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $pembelian->vendor_nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $pembelian->items_count }} item</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">Rp {{ number_format((float) $pembelian->total_nilai, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$pembelian->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($pembelian->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $pembelian->is_posted_to_aset ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $pembelian->is_posted_to_aset ? 'Sudah' : 'Belum' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-6 text-center text-sm text-gray-500">
                                Tidak ada data pembelian yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $laporanPembelian->links() }}
        </div>
    </div>
</div>
@endsection

