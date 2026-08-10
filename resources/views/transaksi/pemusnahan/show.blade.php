@extends('layouts.main')

@section('title', 'Detail Pemusnahan Aset')
@section('page-title', 'Detail Pemusnahan Aset')

@section('content')
<div class="p-6">
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert"><span class="block sm:inline">{{ session('success') }}</span></div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert"><span class="block sm:inline">{{ session('error') }}</span></div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Detail Transaksi Pemusnahan</h3>
            <p class="text-sm text-gray-600 mt-1">Informasi lengkap transaksi {{ $pemusnahan->kode_transaksi }}</p>
        </div>

        <div class="flex space-x-2 items-center">
            <a href="{{ route('transaksi.pemusnahan.index') }}" data-navigate class="btn-c-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </a>

            @if(auth()->user()->is_super_admin)
            <button type="button" @click="$dispatch('delete-modal', { id: {{ $pemusnahan->id }}, kode: '{{ $pemusnahan->kode_transaksi }}' })" class="btn-danger-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                Hapus
            </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Transaksi</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><p class="text-gray-500">Kode Transaksi</p><p class="font-medium text-gray-900">{{ $pemusnahan->kode_transaksi }}</p></div>
                    <div><p class="text-gray-500">Tanggal Pemusnahan</p><p class="font-medium text-gray-900">{{ optional($pemusnahan->tanggal_pemusnahan)->format('d/m/Y') }}</p></div>
                    <div><p class="text-gray-500">Metode Pemusnahan</p><p class="font-medium text-gray-900">{{ $pemusnahan->metode_pemusnahan }}</p></div>
                    <div><p class="text-gray-500">Jumlah Dimusnahkan</p><p class="font-medium text-red-600">{{ $pemusnahan->jumlah_dimusnahkan }} unit</p></div>
                    <div><p class="text-gray-500">Penanggung Jawab</p><p class="font-medium text-gray-900">{{ $pemusnahan->penanggung_jawab }}</p></div>
                    <div><p class="text-gray-500">Nama Pengaju</p><p class="font-medium text-gray-900">{{ $pemusnahan->nama_pengaju ?: '-' }}</p></div>
                    <div><p class="text-gray-500">Unit Pengaju</p><p class="font-medium text-gray-900">{{ $pemusnahan->unit_pengaju ?: '-' }}</p></div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Detail Pemusnahan</h4>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-gray-500">Alasan Pemusnahan</p>
                        <p class="text-gray-900 mt-1">{{ $pemusnahan->alasan_pemusnahan }}</p>
                    </div>
                    @if($pemusnahan->catatan)
                    <div>
                        <p class="text-gray-500">Catatan Tambahan</p>
                        <p class="text-gray-900 mt-1">{{ $pemusnahan->catatan }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Aset</h4>
                <div class="space-y-3 text-sm">
                    <div><p class="text-gray-500">Kode Aset</p><p class="font-medium text-gray-900">{{ $pemusnahan->aset->kode_aset ?? '-' }}</p></div>
                    <div><p class="text-gray-500">Nama Aset</p><p class="font-medium text-gray-900">{{ $pemusnahan->aset->nama_aset ?? '-' }}</p></div>
                    <div><p class="text-gray-500">Stok Saat Ini</p><p class="font-medium text-gray-900">{{ $pemusnahan->aset->jumlah_barang ?? 0 }} unit</p></div>
                    @if($pemusnahan->aset->kategori)
                    <div><p class="text-gray-500">Kategori</p><p class="font-medium text-gray-900">{{ $pemusnahan->aset->kategori->nama_kategori ?? '-' }}</p></div>
                    @endif
                    @if($pemusnahan->aset->lokasi)
                    <div><p class="text-gray-500">Lokasi</p><p class="font-medium text-gray-900">{{ $pemusnahan->aset->lokasi->nama_lokasi ?? '-' }}</p></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div x-data="{ show: false, deleteId: null, kodeTransaksi: '' }"
     @delete-modal.window="show = true; deleteId = $event.detail.id; kodeTransaksi = $event.detail.kode"
     x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="show" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div x-show="show" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Hapus Pemusnahan</h3>
                <p class="text-sm text-gray-500 mt-2">Apakah Anda yakin ingin menghapus transaksi <strong x-text="kodeTransaksi"></strong>?</p>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ url('transaksi/pemusnahan') }}/' + deleteId;
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                " class="btn-danger-sm w-full inline-flex justify-center sm:ml-3 sm:w-auto sm:text-sm">Hapus</button>
                <button type="button" @click="show = false" class="btn-c-outline mt-3 w-full inline-flex justify-center sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
            </div>
        </div>
    </div>
</div>
@endsection
