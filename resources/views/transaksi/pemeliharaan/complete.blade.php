@extends('layouts.main')

@section('title', 'Selesaikan Pemeliharaan')
@section('page-title', 'Selesaikan Pemeliharaan')

@section('content')
<div class="p-6">
    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert"><span class="block sm:inline">{{ session('error') }}</span></div>
    @endif

    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Form Penyelesaian Pemeliharaan</h3>
        <p class="text-sm text-gray-600 mt-1">Nomor transaksi: {{ $pemeliharaan->nomor_pemeliharaan }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('transaksi.pemeliharaan.complete', $pemeliharaan->id) }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" id="tanggal_selesai" name="tanggal_selesai" required value="{{ old('tanggal_selesai', now()->format('Y-m-d\\TH:i')) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    @error('tanggal_selesai')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="kondisi_sesudah_id" class="block text-sm font-medium text-gray-700 mb-1">Kondisi Aset Sesudah <span class="text-red-500">*</span></label>
                    <select id="kondisi_sesudah_id" name="kondisi_sesudah_id" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Kondisi</option>
                        @foreach($kondisis as $kondisi)
                            <option value="{{ $kondisi->id }}" {{ old('kondisi_sesudah_id') == $kondisi->id ? 'selected' : '' }}>{{ $kondisi->nama_kondisi }}</option>
                        @endforeach
                    </select>
                    @error('kondisi_sesudah_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="realisasi_biaya" class="block text-sm font-medium text-gray-700 mb-1">Realisasi Biaya <span class="text-red-500">*</span></label>
                    <input type="number" id="realisasi_biaya" name="realisasi_biaya" min="0" step="1000" required value="{{ old('realisasi_biaya', $pemeliharaan->realisasi_biaya ?? 0) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    @error('realisasi_biaya')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan Penyelesaian</label>
                    <textarea id="catatan" name="catatan" rows="2" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('catatan', $pemeliharaan->catatan) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="tindakan" class="block text-sm font-medium text-gray-700 mb-1">Tindakan yang Dilakukan <span class="text-red-500">*</span></label>
                    <textarea id="tindakan" name="tindakan" rows="4" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('tindakan', $pemeliharaan->tindakan) }}</textarea>
                    @error('tindakan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-6 flex items-center space-x-3">
                <button type="submit" class="btn-a-sm">Selesaikan Pemeliharaan</button>
                <a href="{{ route('transaksi.pemeliharaan.show', $pemeliharaan->id) }}" data-navigate class="btn-c-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

