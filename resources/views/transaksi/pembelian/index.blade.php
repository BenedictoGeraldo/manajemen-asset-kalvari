@extends('layouts.main')

@section('title', 'Transaksi Pembelian')
@section('page-title', 'Transaksi Pembelian')

@section('content')
<div class="p-6">
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Daftar Transaksi Pembelian</h3>
            <p class="text-sm text-gray-600 mt-1">Kelola transaksi pembelian sebelum diposting ke data aset.</p>
        </div>

        @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pembelian.create'))
        <a href="{{ route('transaksi.pembelian.create') }}" data-navigate
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Pembelian
        </a>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-4 mb-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nomor / vendor"
                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

            <select name="status" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="diajukan" {{ $status === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                <option value="disetujui" {{ $status === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="dibatalkan" {{ $status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>

            <select name="per_page" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @foreach([10, 25, 50] as $size)
                    <option value="{{ $size }}" {{ (int) $perPage === $size ? 'selected' : '' }}>{{ $size }} / halaman</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded-lg text-sm">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Pembelian</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pembelians as $pembelian)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $pembelian->nomor_pembelian }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ optional($pembelian->tanggal_pembelian)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $pembelian->vendor_nama }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $pembelian->items_count }} item</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Rp {{ number_format($pembelian->total_nilai, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-700',
                                        'diajukan' => 'bg-yellow-100 text-yellow-800',
                                        'disetujui' => 'bg-green-100 text-green-800',
                                        'dibatalkan' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$pembelian->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($pembelian->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('transaksi.pembelian.show', $pembelian->id) }}" data-navigate class="text-blue-600 hover:text-blue-800">Detail</a>
                                    @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pembelian.edit')) && $pembelian->status !== 'disetujui')
                                        <a href="{{ route('transaksi.pembelian.edit', $pembelian->id) }}" data-navigate class="text-indigo-600 hover:text-indigo-800">Edit</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada transaksi pembelian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $pembelians->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
