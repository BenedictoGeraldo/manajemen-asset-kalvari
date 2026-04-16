@extends('layouts.main')

@section('title', 'Laporan Pemusnahan Aset')
@section('page-title', 'Laporan Pemusnahan')

@section('content')
<div class="bg-white rounded-lg shadow p-6 content-fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Laporan Pemusnahan Aset</h2>
            <p class="text-gray-600">Rekapitulasi data penghapusan aset.</p>
        </div>
        <div class="flex space-x-2">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="btn-secondary flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-50">
                    <a href="{{ route('laporan.pemusnahan.export', 'xlsx') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Excel (.xlsx)</a>
                    <a href="{{ route('laporan.pemusnahan.export', 'csv') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">CSV (.csv)</a>
                    <a href="{{ route('laporan.pemusnahan.export', 'pdf') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">PDF (.pdf)</a>
                </div>
            </div>
        </div>
    </div>

    <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="col-span-1 md:col-span-2">
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari kode atau nama aset..." 
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <button type="submit" class="btn-primary w-full">Filter</button>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aset</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alasan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PJ</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($laporanPemusnahan as $p)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $p->tanggal_pemusnahan->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">{{ $p->aset->nama_aset }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $p->jumlah_dimusnahkan }} unit</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $p->metode_pemusnahan }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $p->alasan_pemusnahan }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $p->penanggung_jawab }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Data tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $laporanPemusnahan->links() }}
    </div>
</div>
@endsection
