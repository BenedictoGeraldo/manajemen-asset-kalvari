@extends('layouts.main')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Selesaikan Mutasi Aset</h3>
        <p class="text-sm text-gray-600 mt-1">
            <span class="font-medium">Nomor:</span> {{ $mutasiAset->nomor_mutasi }}
        </p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('transaksi.mutasi_aset.complete', $mutasiAset->id) }}" method="POST" class="space-y-6">
            @csrf

            <!-- Aset Info (read-only) -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">Aset yang Dimutasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Kode Aset</p>
                        <p class="font-medium text-gray-900 mt-1">{{ $mutasiAset->aset->kode_aset }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Nama Aset</p>
                        <p class="font-medium text-gray-900 mt-1">{{ $mutasiAset->aset->nama_aset }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Jenis Mutasi</p>
                        <p class="font-medium text-gray-900 mt-1">{{ ucfirst(str_replace('_', ' ', $mutasiAset->jenis_mutasi)) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Status</p>
                        <p class="font-medium text-gray-900 mt-1">{{ ucfirst($mutasiAset->status) }}</p>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">
                    Catatan Penyelesaian
                </label>
                <textarea id="catatan" name="catatan" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" placeholder="Catatan pelengkap tentang penyelesaian mutasi">{{ old('catatan', $mutasiAset->catatan ?? '') }}</textarea>
                @error('catatan')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4 border-t">
                <button type="submit" class="btn-export inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Selesaikan Mutasi
                </button>
                <a href="{{ route('transaksi.mutasi_aset.show', $mutasiAset->id) }}" data-navigate class="btn-c-outline">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

