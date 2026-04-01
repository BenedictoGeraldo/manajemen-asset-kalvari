@extends('layouts.main')

@section('title', 'Detail Pemeliharaan')
@section('page-title', 'Detail Pemeliharaan')

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
            <h3 class="text-lg font-semibold text-gray-800">Detail Transaksi Pemeliharaan</h3>
            <p class="text-sm text-gray-600 mt-1">Informasi lengkap transaksi {{ $pemeliharaan->nomor_pemeliharaan }}</p>
        </div>

        <div class="flex space-x-2 items-center">
            <a href="{{ route('transaksi.pemeliharaan.index') }}" data-navigate class="btn-c-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </a>

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pemeliharaan.edit')) && $pemeliharaan->canEdit())
            <a href="{{ route('transaksi.pemeliharaan.edit', $pemeliharaan->id) }}" data-navigate class="btn-b-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                Edit
            </a>
            @endif

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pemeliharaan.approve')) && in_array($pemeliharaan->status, ['draft', 'diajukan']))
            <form action="{{ route('transaksi.pemeliharaan.approve', $pemeliharaan->id) }}" method="POST" class="inline-flex">@csrf
                <button type="submit" class="btn-export-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Setujui
                </button>
            </form>
            <form action="{{ route('transaksi.pemeliharaan.reject', $pemeliharaan->id) }}" method="POST" class="inline-flex">@csrf
                <button type="submit" class="btn-danger-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    Tolak
                </button>
            </form>
            @endif

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pemeliharaan.process')) && $pemeliharaan->status === 'disetujui')
            <form action="{{ route('transaksi.pemeliharaan.process', $pemeliharaan->id) }}" method="POST" class="inline-flex">@csrf
                <button type="submit" class="btn-a-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                    Mulai Proses
                </button>
            </form>
            @endif

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pemeliharaan.complete')) && $pemeliharaan->status === 'proses')
            <a href="{{ route('transaksi.pemeliharaan.complete.form', $pemeliharaan->id) }}" data-navigate class="btn-a-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Selesaikan
            </a>
            @endif

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pemeliharaan.delete')) && $pemeliharaan->canDelete())
            <button type="button" @click="$dispatch('delete-modal', { id: {{ $pemeliharaan->id }}, nomor: '{{ $pemeliharaan->nomor_pemeliharaan }}' })" class="btn-danger-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                Hapus
            </button>
            @endif
        </div>
    </div>

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Transaksi</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><p class="text-gray-500">Nomor Pemeliharaan</p><p class="font-medium text-gray-900">{{ $pemeliharaan->nomor_pemeliharaan }}</p></div>
                    <div><p class="text-gray-500">Status</p><p class="mt-1"><span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$pemeliharaan->status] ?? 'bg-gray-100 text-gray-700' }}">{{ ucfirst($pemeliharaan->status) }}</span></p></div>
                    <div><p class="text-gray-500">Tanggal Pengajuan</p><p class="font-medium text-gray-900">{{ optional($pemeliharaan->tanggal_pengajuan)->format('d/m/Y') }}</p></div>
                    <div><p class="text-gray-500">Tanggal Rencana</p><p class="font-medium text-gray-900">{{ optional($pemeliharaan->tanggal_rencana)->format('d/m/Y') ?: '-' }}</p></div>
                    <div><p class="text-gray-500">Jenis Pemeliharaan</p><p class="font-medium text-gray-900">{{ ucfirst($pemeliharaan->jenis_pemeliharaan) }}</p></div>
                    <div><p class="text-gray-500">Prioritas</p><p class="font-medium text-gray-900">{{ ucfirst($pemeliharaan->prioritas) }}</p></div>
                    <div><p class="text-gray-500">Vendor / Teknisi</p><p class="font-medium text-gray-900">{{ $pemeliharaan->vendor_nama ?: '-' }}</p></div>
                    <div><p class="text-gray-500">Kontak Vendor</p><p class="font-medium text-gray-900">{{ $pemeliharaan->vendor_kontak ?: '-' }}</p></div>
                    <div><p class="text-gray-500">Estimasi Biaya</p><p class="font-medium text-gray-900">Rp {{ number_format($pemeliharaan->estimasi_biaya, 0, ',', '.') }}</p></div>
                    <div><p class="text-gray-500">Realisasi Biaya</p><p class="font-medium text-gray-900">Rp {{ number_format($pemeliharaan->realisasi_biaya, 0, ',', '.') }}</p></div>
                    <div><p class="text-gray-500">Tanggal Mulai</p><p class="font-medium text-gray-900">{{ optional($pemeliharaan->tanggal_mulai)->format('d/m/Y H:i') ?: '-' }}</p></div>
                    <div><p class="text-gray-500">Tanggal Selesai</p><p class="font-medium text-gray-900">{{ optional($pemeliharaan->tanggal_selesai)->format('d/m/Y H:i') ?: '-' }}</p></div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Detail Pemeliharaan</h4>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-gray-500">Keluhan / Tujuan</p>
                        <p class="text-gray-900 mt-1">{{ $pemeliharaan->keluhan ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tindakan</p>
                        <p class="text-gray-900 mt-1">{{ $pemeliharaan->tindakan ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Catatan</p>
                        <p class="text-gray-900 mt-1">{{ $pemeliharaan->catatan ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Catatan Approval</p>
                        <p class="text-gray-900 mt-1">{{ $pemeliharaan->catatan_approval ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Aset</h4>
                <div class="space-y-3 text-sm">
                    <div><p class="text-gray-500">Kode Aset</p><p class="font-medium text-gray-900">{{ $pemeliharaan->aset->kode_aset ?? '-' }}</p></div>
                    <div><p class="text-gray-500">Nama Aset</p><p class="font-medium text-gray-900">{{ $pemeliharaan->aset->nama_aset ?? '-' }}</p></div>
                    <div><p class="text-gray-500">Kategori</p><p class="font-medium text-gray-900">{{ $pemeliharaan->aset->kategori->nama_kategori ?? '-' }}</p></div>
                    <div><p class="text-gray-500">Lokasi</p><p class="font-medium text-gray-900">{{ $pemeliharaan->aset->lokasi->nama_lokasi ?? '-' }}</p></div>
                    <div><p class="text-gray-500">Kondisi Sebelum</p><p class="font-medium text-gray-900">{{ $pemeliharaan->kondisiSebelum->nama_kondisi ?? '-' }}</p></div>
                    <div><p class="text-gray-500">Kondisi Sesudah</p><p class="font-medium text-gray-900">{{ $pemeliharaan->kondisiSesudah->nama_kondisi ?? '-' }}</p></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div x-data="{ show: false, deleteId: null, nomorPemeliharaan: '' }" @delete-modal.window="show = true; deleteId = $event.detail.id; nomorPemeliharaan = $event.detail.nomor" x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="show" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div x-show="show" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4"><h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Hapus Pemeliharaan</h3><p class="text-sm text-gray-500 mt-2">Apakah Anda yakin ingin menghapus transaksi <strong x-text="nomorPemeliharaan"></strong>?</p></div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" @click="
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ url('transaksi/pemeliharaan') }}/' + deleteId;
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

