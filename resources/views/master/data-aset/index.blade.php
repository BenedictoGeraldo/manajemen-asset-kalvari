@extends('layouts.main')

@section('title', 'Data Aset')
@section('page-title', 'Data Aset')

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
            <h3 class="text-lg font-semibold text-gray-800">Daftar Aset Gereja</h3>
            <p class="text-sm text-gray-600 mt-1">Total: {{ $asets->count() }} record aset</p>
        </div>
        <div class="flex space-x-3">
            <!-- Export Dropdown -->
            @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('data-aset.export'))
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
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
                        <a href="{{ route('data-aset.export', 'xlsx') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L13 1.586A2 2 0 0011.586 1H9z"/>
                            </svg>
                            Export ke Excel (.xlsx)
                        </a>
                        <a href="{{ route('data-aset.export', 'csv') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L13 1.586A2 2 0 0011.586 1H9z"/>
                            </svg>
                            Export ke CSV (.csv)
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('data-aset.create'))
            <a href="{{ route('data-aset.create') }}" data-navigate
               class="inline-flex items-center px-5 py-2.5 !bg-blue-600 hover:!bg-blue-800 !text-white hover:!text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                <svg class="w-5 h-5 mr-2 !text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Aset
            </a>
            @endif
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Aset</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Aset</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengelola</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($asets as $aset)
                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('data-aset.show', $aset->id) }}'">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $aset->kode_aset }}</div>
                                <div class="text-xs text-gray-500">{{ $aset->tahun_pengadaan }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $aset->nama_aset }}</div>
                                <div class="text-xs text-gray-500">{{ Str::limit($aset->deskripsi_aset, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">{{ $aset->kategori->nama_kategori }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700">{{ $aset->lokasi->nama_lokasi }}</div>
                                <div class="text-xs text-gray-500">{{ $aset->lokasi->gedung }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $aset->jumlah_barang }}
                                <span class="text-xs text-gray-500">({{ $aset->tipe_grup }})</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">{{ $aset->kondisi->nama_kondisi }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700">{{ $aset->pengelola->nama_pengelola }}</div>
                                <div class="text-xs text-gray-500">{{ $aset->pengelola->jabatan }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation()">
                                <div class="flex space-x-2">
                                    <a href="{{ route('data-aset.show', $aset->id) }}" data-navigate
                                       class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('data-aset.edit'))
                                    <a href="{{ route('data-aset.edit', $aset->id) }}" data-navigate
                                       class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @endif
                                    @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('data-aset.delete'))
                                    <button type="button" @click="$dispatch('delete-modal', { id: {{ $aset->id }}, name: '{{ $aset->nama_aset }}' })" class="text-red-600 hover:text-red-900" title="Hapus">
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
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                Belum ada data aset
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div x-data="{
    show: false,
    deleteId: null,
    assetName: ''
}"
@delete-modal.window="show = true; deleteId = $event.detail.id; assetName = $event.detail.name"
 x-show="show"
 x-cloak
 class="fixed inset-0 z-50 overflow-y-auto"
 aria-labelledby="modal-title"
 role="dialog"
 aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
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

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
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
                            Konfirmasi Hapus Aset
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus aset <strong x-text="assetName"></strong>?
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
                            form.action = '{{ url('data-aset') }}/' + deleteId;
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
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-150">
                    Hapus
                </button>
                <button type="button"
                        @click="show = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-150">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
