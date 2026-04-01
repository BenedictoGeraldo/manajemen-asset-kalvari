@extends('layouts.main')

@section('title', 'Serah Terima Peminjaman')
@section('page-title', 'Serah Terima Peminjaman')

@section('content')
<div class="p-6">
    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Serah Terima Aset</h3>
        <p class="text-sm text-gray-600 mt-1">Nomor transaksi: {{ $peminjaman->nomor_peminjaman }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('transaksi.peminjaman.handover', $peminjaman->id) }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="tanggal_serah_terima" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Serah Terima <span class="text-red-500">*</span></label>
                    <input type="datetime-local" id="tanggal_serah_terima" name="tanggal_serah_terima" required
                           value="{{ old('tanggal_serah_terima', now()->format('Y-m-d\\TH:i')) }}"
                           class="w-full rounded-lg @error('tanggal_serah_terima') border-red-500 @else border-gray-300 @enderror focus:border-blue-500 focus:ring-blue-500">
                    @error('tanggal_serah_terima')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="catatan_serah_terima" class="block text-sm font-medium text-gray-700 mb-1">Catatan Serah Terima</label>
                    <textarea id="catatan_serah_terima" name="catatan_serah_terima" rows="2" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('catatan_serah_terima') }}</textarea>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($peminjaman->items as $index => $item)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="md:col-span-2">
                                <p class="text-sm font-medium text-gray-900">{{ $item->aset_nama_snapshot }}</p>
                                <p class="text-xs text-gray-500">{{ $item->aset_kode_snapshot }} | Qty: {{ $item->jumlah }}</p>
                            </div>

                            <div>
                                <label class="text-sm text-gray-700">Kondisi Awal</label>
                                <select name="items[{{ $index }}][kondisi_awal_id]" class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Pilih Kondisi</option>
                                    @foreach($kondisis as $kondisi)
                                        <option value="{{ $kondisi->id }}" {{ old("items.$index.kondisi_awal_id") == $kondisi->id ? 'selected' : '' }}>{{ $kondisi->nama_kondisi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-3">
                                <label class="text-sm text-gray-700">Catatan Item Saat Serah Terima</label>
                                <textarea name="items[{{ $index }}][catatan_serah_terima]" rows="2" class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old("items.$index.catatan_serah_terima") }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex items-center space-x-3">
                <button type="submit" class="btn-a-sm">Proses Serah Terima</button>
                <a href="{{ route('transaksi.peminjaman.show', $peminjaman->id) }}" data-navigate class="btn-c-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

