@extends('layouts.main')

@section('title', 'Daftar Pemusnahan Aset')
@section('page-title', 'Pemusnahan Aset')

@section('content')
<div class="bg-white rounded-lg shadow p-6 content-fade-in">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Pemusnahan Aset</h2>
            <p class="text-gray-600">Riwayat penghapusan aset dari inventaris.</p>
        </div>
        <div class="flex space-x-2">
                <a href="{{ route('transaksi.pemusnahan.create') }}" class="btn-a-sm flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Catat Pemusnahan
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aset</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penanggung Jawab</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengaju</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pemusnahans as $p)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">{{ $p->kode_transaksi }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                        <div class="font-semibold">{{ $p->aset->nama_aset }}</div>
                        <div class="text-xs text-gray-500">{{ $p->aset->kode_aset }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $p->jumlah_dimusnahkan }} unit</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $p->tanggal_pemusnahan->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $p->metode_pemusnahan }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $p->penanggung_jawab }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        <div class="font-medium text-gray-900">{{ $p->nama_pengaju ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $p->unit_pengaju ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('transaksi.pemusnahan.show', $p->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500 italic">Belum ada data pemusnahan aset.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $pemusnahans->links() }}
    </div>
</div>
@endsection
