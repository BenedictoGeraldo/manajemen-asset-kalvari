@extends('layouts.main')

@section('title', 'Detail Peminjaman')
@section('page-title', 'Detail Peminjaman')

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

    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Detail Transaksi Peminjaman</h3>
            <p class="text-sm text-gray-600 mt-1">Informasi lengkap transaksi {{ $peminjaman->nomor_peminjaman }}</p>
        </div>

        <div class="flex space-x-2 items-center">
            <a href="{{ route('transaksi.peminjaman.index') }}" data-navigate
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.peminjaman.edit')) && $peminjaman->canEdit())
            <a href="{{ route('transaksi.peminjaman.edit', $peminjaman->id) }}" data-navigate
               class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            @endif

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.peminjaman.approve')) && in_array($peminjaman->status, ['draft', 'diajukan']))
            <form action="{{ route('transaksi.peminjaman.approve', $peminjaman->id) }}" method="POST" class="inline-flex">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Setujui
                </button>
            </form>

            <form action="{{ route('transaksi.peminjaman.reject', $peminjaman->id) }}" method="POST" class="inline-flex">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Tolak
                </button>
            </form>
            @endif

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.peminjaman.handover')) && $peminjaman->status === 'disetujui')
            <a href="{{ route('transaksi.peminjaman.handover.form', $peminjaman->id) }}" data-navigate
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                Serah Terima
            </a>
            @endif

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.peminjaman.return')) && in_array($peminjaman->status, ['dipinjam', 'terlambat']))
            <a href="{{ route('transaksi.peminjaman.return.form', $peminjaman->id) }}" data-navigate
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Pengembalian
            </a>
            @endif

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.peminjaman.delete')) && $peminjaman->canDelete())
            <button type="button"
                    @click="$dispatch('delete-modal', { id: {{ $peminjaman->id }}, nomor: '{{ $peminjaman->nomor_peminjaman }}' })"
                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Hapus
            </button>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="mb-4 pb-4 border-b">
            <h4 class="text-lg font-semibold text-gray-900">{{ $peminjaman->nomor_peminjaman }}</h4>
            <p class="text-sm text-gray-600 mt-1">Peminjam: {{ $peminjaman->nama_peminjam }}</p>
        </div>

        @php
            $statusColors = [
                'draft' => 'bg-gray-100 text-gray-700',
                'diajukan' => 'bg-yellow-100 text-yellow-800',
                'disetujui' => 'bg-blue-100 text-blue-800',
                'ditolak' => 'bg-red-100 text-red-700',
                'dipinjam' => 'bg-indigo-100 text-indigo-800',
                'dikembalikan' => 'bg-green-100 text-green-800',
                'terlambat' => 'bg-orange-100 text-orange-800',
                'dibatalkan' => 'bg-gray-200 text-gray-700',
            ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 text-sm">
            <div>
                <p class="text-gray-500">Tanggal Pengajuan</p>
                <p class="font-medium text-gray-900">{{ optional($peminjaman->tanggal_pengajuan)->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Rencana Kembali</p>
                <p class="font-medium text-gray-900">{{ optional($peminjaman->tanggal_rencana_kembali)->format('d/m/Y') ?: '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Status</p>
                <p class="mt-1">
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$peminjaman->status] ?? 'bg-gray-100 text-gray-700' }}">{{ ucfirst($peminjaman->status) }}</span>
                </p>
            </div>
            <div>
                <p class="text-gray-500">Kontak Peminjam</p>
                <p class="font-medium text-gray-900">{{ $peminjaman->kontak_peminjam ?: '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Unit / Departemen</p>
                <p class="font-medium text-gray-900">{{ $peminjaman->unit_peminjam ?: '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Tanggal Disetujui</p>
                <p class="font-medium text-gray-900">{{ optional($peminjaman->tanggal_disetujui)->format('d/m/Y H:i') ?: '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Tanggal Serah Terima</p>
                <p class="font-medium text-gray-900">{{ optional($peminjaman->tanggal_serah_terima)->format('d/m/Y H:i') ?: '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Tanggal Dikembalikan</p>
                <p class="font-medium text-gray-900">{{ optional($peminjaman->tanggal_dikembalikan)->format('d/m/Y H:i') ?: '-' }}</p>
            </div>
        </div>

        @if($peminjaman->keperluan)
            <div class="mt-4 border-t pt-4">
                <p class="text-sm text-gray-500">Keperluan</p>
                <p class="text-sm text-gray-800 mt-1">{{ $peminjaman->keperluan }}</p>
            </div>
        @endif

        @if($peminjaman->catatan)
            <div class="mt-4 border-t pt-4">
                <p class="text-sm text-gray-500">Catatan</p>
                <p class="text-sm text-gray-800 mt-1">{{ $peminjaman->catatan }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aset</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok Saat Ini</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi Awal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi Akhir</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($peminjaman->items as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-900">{{ $item->aset_nama_snapshot }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $item->aset_kode_snapshot ?: '-' }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $item->jumlah }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $item->aset->jumlah_barang ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $item->kondisiAwal->nama_kondisi ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $item->kondisiAkhir->nama_kondisi ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div x-data="{ show: false, deleteId: null, nomorPeminjaman: '' }"
     @delete-modal.window="show = true; deleteId = $event.detail.id; nomorPeminjaman = $event.detail.nomor"
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="show" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="show"
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Konfirmasi Hapus Peminjaman</h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus transaksi <strong x-text="nomorPeminjaman"></strong>?</p>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button"
                        @click="
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ url('transaksi/peminjaman') }}/' + deleteId;
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
                        "
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                    Hapus
                </button>
                <button type="button" @click="show = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
