@extends('layouts.main')

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

    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Data Transaksi Mutasi Aset</h3>
        <p class="text-sm text-gray-600 mt-1">Kelola mutasi aset dari pengajuan hingga penyelesaian.</p>
    </div>

    <div class="mb-6">
        <div class="w-full flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-2 sm:space-y-0">
            <form method="GET" class="flex-1">
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari mutasi berdasarkan nomor atau aset..."
                      class="flex-1 w-full px-4 py-3 border border-gray-300 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="off">
            </form>

            <div class="flex space-x-3 mt-2 sm:mt-0">
                @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.mutasi_aset.export'))
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
                    <div x-show="open" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                        <div class="py-1">
                            <a href="{{ route('transaksi.mutasi_aset.export', 'xlsx') }}" class="dropdown-export-item">
                                <svg class="w-4 h-4 mr-2 export-icon-xlsx" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L13 1.586A2 2 0 0011.586 1H9z"/></svg>
                                Export ke Excel (.xlsx)
                            </a>
                            <a href="{{ route('transaksi.mutasi_aset.export', 'csv') }}" class="dropdown-export-item">
                                <svg class="w-4 h-4 mr-2 export-icon-csv" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L13 1.586A2 2 0 0011.586 1H9z"/></svg>
                                Export ke CSV (.csv)
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.mutasi_aset.create'))
                <a href="{{ route('transaksi.mutasi_aset.create') }}" data-navigate
                   class="btn-a">
                    <svg class="w-5 h-5 mr-2 !text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Mutasi Aset
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Mutasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aset</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Mutasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($mutasis as $mutasi)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $mutasi->nomor_mutasi }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $mutasi->tanggal_mutasi->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <p class="font-medium text-gray-900">{{ $mutasi->aset->nama_aset }}</p>
                                <p class="text-gray-600">{{ $mutasi->aset->kode_aset }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $mutasi->jenis_mutasi)) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
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
                                <span class="px-2 py-1 inline-flex rounded-full text-xs font-medium {{ $statusColors[$mutasi->status] ?? '' }}">
                                    {{ ucfirst($mutasi->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('transaksi.mutasi_aset.show', $mutasi->id) }}" data-navigate class="text-blue-600 hover:text-blue-900 inline-flex items-center px-3 py-1 rounded-md hover:bg-blue-50" title="Lihat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                @if($mutasi->canEdit())
                                    <a href="{{ route('transaksi.mutasi_aset.edit', $mutasi->id) }}" data-navigate class="text-yellow-600 hover:text-yellow-900 inline-flex items-center px-3 py-1 rounded-md hover:bg-yellow-50" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                @endif
                                @if($mutasi->canDelete())
                                    <button type="button" @click="$dispatch('delete-modal', { id: {{ $mutasi->id }}, nomor: '{{ $mutasi->nomor_mutasi }}' })" class="text-red-600 hover:text-red-900 inline-flex items-center px-3 py-1 rounded-md hover:bg-red-50" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada data mutasi aset
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $mutasis->links() }}
    </div>

    <!-- Delete Modal -->
    <div x-data="{ showDelete: false, deleteId: null, deleteNomor: '' }" @delete-modal.window="showDelete = true; deleteId = $event.detail.id; deleteNomor = $event.detail.nomor" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" x-show="showDelete" x-cloak>
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus Mutasi Aset</h3>
            <p class="text-gray-600 mb-4">Anda akan menghapus mutasi aset <span x-text="deleteNomor" class="font-medium"></span>. Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <form :action="`{{ route('transaksi.mutasi_aset.destroy', '') }}/${deleteId}`" method="POST" class="flex-1">@csrf @method('DELETE')
                    <button type="submit" class="btn-danger-sm w-full">
                        Hapus
                    </button>
                </form>
                <button @click="showDelete = false" class="btn-c-outline flex-1">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

