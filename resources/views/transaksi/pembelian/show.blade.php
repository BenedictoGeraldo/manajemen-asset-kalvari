@extends('layouts.main')

@section('title', 'Detail Pembelian')
@section('page-title', 'Detail Pembelian')

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

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Detail Transaksi Pembelian</h3>
            <p class="text-sm text-gray-600 mt-1">Informasi lengkap transaksi {{ $pembelian->nomor_pembelian }}</p>
        </div>

        <div class="flex space-x-2 items-center">
            <a href="{{ route('transaksi.pembelian.index') }}" data-navigate
               class="btn-c-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pembelian.edit')) && $pembelian->status !== 'disetujui')
            <a href="{{ route('transaksi.pembelian.edit', $pembelian->id) }}" data-navigate
               class="btn-b-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            @endif

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pembelian.approve')) && $pembelian->status !== 'disetujui')
            <form action="{{ route('transaksi.pembelian.approve', $pembelian->id) }}" method="POST" onsubmit="return confirm('Setujui pembelian ini dan posting ke data aset?')" class="inline-flex">
                @csrf
                <button type="submit" class="btn-export-sm" title="Setujui & Posting">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Setujui
                </button>
            </form>
            @endif

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pembelian.delete')) && $pembelian->status !== 'disetujui')
            <button type="button"
                    @click="$dispatch('delete-modal', { id: {{ $pembelian->id }}, nomor: '{{ $pembelian->nomor_pembelian }}' })"
                    class="btn-danger-sm" title="Hapus">
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
            <h4 class="text-lg font-semibold text-gray-900">{{ $pembelian->nomor_pembelian }}</h4>
            <p class="text-sm text-gray-600 mt-1">Vendor: {{ $pembelian->vendor_nama }}</p>
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

<!-- Delete Confirmation Modal -->
<div x-data="{ show: false, deleteId: null, nomorPembelian: '' }"
     @delete-modal.window="show = true; deleteId = $event.detail.id; nomorPembelian = $event.detail.nomor"
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
             @click="show = false"
             aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Konfirmasi Hapus Pembelian
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus transaksi <strong x-text="nomorPembelian"></strong>?
                                Data yang sudah dihapus tidak dapat dikembalikan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button"
                        @click="
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ url('transaksi/pembelian') }}/' + deleteId;
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
                        class="btn-danger-sm w-full inline-flex justify-center sm:ml-3 sm:w-auto sm:text-sm">
                    Hapus
                </button>
                <button type="button"
                        @click="show = false"
                        class="btn-c-outline mt-3 w-full inline-flex justify-center sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

