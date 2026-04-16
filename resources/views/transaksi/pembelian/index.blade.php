@extends('layouts.main')

@section('title', 'Transaksi Pembelian')
@section('page-title', 'Transaksi Pembelian')

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
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Data Transaksi Pembelian</h3>
        <p class="text-sm text-gray-600 mt-1">Kelola transaksi pembelian sebelum diposting ke data aset.</p>
    </div>

    <div class="mb-6">
        <div class="w-full flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-2 sm:space-y-0">
            <form method="GET" class="flex-1">
                <div class="search-input-wrapper">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari pembelian berdasarkan nomor atau vendor..."
                          class="search-input-control flex-1 w-full px-4 py-3 border border-gray-300 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="off">
                    <button type="submit" class="search-submit-btn" aria-label="Cari">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="hidden" name="per_page" value="{{ $perPage }}">
            </form>

            <div class="flex space-x-3 mt-2 sm:mt-0">
                @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pembelian.view'))
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false"
                            class="btn-export">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Data
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                        <div class="py-1">
                                     <a href="{{ route('transaksi.pembelian.export', 'xlsx') }}"
                                         class="dropdown-export-item">
                                <svg class="w-4 h-4 mr-2 export-icon-xlsx" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L13 1.586A2 2 0 0011.586 1H9z"/>
                                </svg>
                                Export ke Excel (.xlsx)
                            </a>
                                     <a href="{{ route('transaksi.pembelian.export', 'csv') }}"
                                         class="dropdown-export-item">
                                <svg class="w-4 h-4 mr-2 export-icon-csv" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L13 1.586A2 2 0 0011.586 1H9z"/>
                                </svg>
                                Export ke CSV (.csv)
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pembelian.create'))
                <a href="{{ route('transaksi.pembelian.create') }}" data-navigate
                   class="btn-a">
                    <svg class="w-5 h-5 mr-2 !text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Pembelian
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Pembelian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pembelians as $index => $pembelian)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ($pembelians->firstItem() ?? 1) + $index }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $pembelian->nomor_pembelian }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ optional($pembelian->tanggal_pembelian)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $pembelian->vendor_nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $pembelian->items_count }} item</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp {{ number_format($pembelian->total_nilai, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('transaksi.pembelian.show', $pembelian->id) }}" data-navigate class="action-view" title="Lihat Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pembelian.edit')) && $pembelian->status !== 'disetujui')
                                    <a href="{{ route('transaksi.pembelian.edit', $pembelian->id) }}" data-navigate class="action-edit" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @endif
                                    @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pembelian.delete')) && $pembelian->status !== 'disetujui')
                                    <button type="button"
                                            @click="$dispatch('delete-modal', { id: {{ $pembelian->id }}, nomor: '{{ $pembelian->nomor_pembelian }}' })"
                                            class="action-delete" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada transaksi pembelian.</td>
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

