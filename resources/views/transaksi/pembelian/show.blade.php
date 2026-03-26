@extends('layouts.main')

@section('title', 'Detail Pembelian')
@section('page-title', 'Detail Pembelian')

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

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col md:flex-row md:justify-between gap-4">
            <div>
                <h3 class="text-xl font-semibold text-gray-900">{{ $pembelian->nomor_pembelian }}</h3>
                <p class="text-sm text-gray-600 mt-1">Vendor: {{ $pembelian->vendor_nama }}</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('transaksi.pembelian.index') }}" data-navigate class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm">Kembali</a>

                @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pembelian.edit')) && $pembelian->status !== 'disetujui')
                    <a href="{{ route('transaksi.pembelian.edit', $pembelian->id) }}" data-navigate class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">Edit</a>
                @endif

                @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pembelian.approve')) && $pembelian->status !== 'disetujui')
                    <form action="{{ route('transaksi.pembelian.approve', $pembelian->id) }}" method="POST" onsubmit="return confirm('Setujui pembelian ini dan posting ke data aset?')">
                        @csrf
                        <button type="submit" class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm">Setujui & Posting</button>
                    </form>
                @endif

                @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pembelian.delete')) && $pembelian->status !== 'disetujui')
                    <form action="{{ route('transaksi.pembelian.destroy', $pembelian->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi pembelian ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm">Hapus</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 text-sm">
            <div>
                <p class="text-gray-500">Tanggal Pembelian</p>
                <p class="font-medium text-gray-900">{{ optional($pembelian->tanggal_pembelian)->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Status</p>
                <p class="font-medium text-gray-900">{{ ucfirst($pembelian->status) }}</p>
            </div>
            <div>
                <p class="text-gray-500">Total Nilai</p>
                <p class="font-medium text-gray-900">Rp {{ number_format($pembelian->total_nilai, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Sumber Dana</p>
                <p class="font-medium text-gray-900">{{ $pembelian->sumber_dana ?: '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Kontak Vendor</p>
                <p class="font-medium text-gray-900">{{ $pembelian->vendor_kontak ?: '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Sudah Diposting ke Aset</p>
                <p class="font-medium text-gray-900">{{ $pembelian->is_posted_to_aset ? 'Ya' : 'Belum' }}</p>
            </div>
        </div>

        @if($pembelian->catatan)
            <div class="mt-4 border-t pt-4">
                <p class="text-sm text-gray-500">Catatan</p>
                <p class="text-sm text-gray-800 mt-1">{{ $pembelian->catatan }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga Satuan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aset Kolektif</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pembelian->items as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-900">{{ $item->nama_item }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $item->kategori->nama_kategori ?? '-' }} | {{ $item->lokasi->nama_lokasi ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $item->jumlah }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                @if($item->aset_kolektif_id)
                                    <span class="text-green-700">Terposting (#{{ $item->aset_kolektif_id }})</span>
                                @else
                                    <span class="text-gray-500">Belum</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
