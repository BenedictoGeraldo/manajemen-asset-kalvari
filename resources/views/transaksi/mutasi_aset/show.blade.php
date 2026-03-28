@extends('layouts.main')

@section('title', 'Detail Mutasi Aset')
@section('page-title', 'Detail Mutasi Aset')

@section('content')
<div x-data="{ showDelete: false }" class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Detail Transaksi Mutasi Aset</h3>
            <p class="text-sm text-gray-600 mt-1">Informasi lengkap transaksi {{ $mutasi->nomor_mutasi }}</p>
        </div>
        <div class="flex space-x-2 items-center">
            <a href="{{ route('transaksi.mutasi_aset.index') }}" data-navigate class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.mutasi_aset.edit')) && $mutasi->canEdit())
                <a href="{{ route('transaksi.mutasi_aset.edit', $mutasi->id) }}" data-navigate class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
            @endif

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.mutasi_aset.approve')) && in_array($mutasi->status, ['draft', 'diajukan']))
                <form action="{{ route('transaksi.mutasi_aset.approve', $mutasi->id) }}" method="POST" class="inline-flex">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Setujui
                    </button>
                </form>

                <form action="{{ route('transaksi.mutasi_aset.reject', $mutasi->id) }}" method="POST" class="inline-flex">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Tolak
                    </button>
                </form>
            @endif

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.mutasi_aset.process')) && $mutasi->status === 'disetujui')
                <form action="{{ route('transaksi.mutasi_aset.process', $mutasi->id) }}" method="POST" class="inline-flex">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        Mulai Proses
                    </button>
                </form>
            @endif

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.mutasi_aset.complete')) && $mutasi->status === 'proses')
                <a href="{{ route('transaksi.mutasi_aset.complete.form', $mutasi->id) }}" data-navigate class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Selesaikan
                </a>
            @endif

            @if((auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.mutasi_aset.delete')) && $mutasi->canDelete())
                <button @click="showDelete = true" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </button>
            @endif
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Status Transaksi</h3>
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
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$mutasi->status] ?? '' }}">
                        {{ ucfirst($mutasi->status) }}
                    </span>
                </div>
            </div>

            <!-- Informasi Transaksi -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Transaksi</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="border-b pb-3">
                        <p class="text-sm text-gray-600">Nomor Mutasi</p>
                        <p class="font-medium text-gray-900 mt-1">{{ $mutasi->nomor_mutasi }}</p>
                    </div>
                    <div class="border-b pb-3">
                        <p class="text-sm text-gray-600">Tanggal Mutasi</p>
                        <p class="font-medium text-gray-900 mt-1">{{ $mutasi->tanggal_mutasi->format('d/m/Y') }}</p>
                    </div>
                    <div class="border-b pb-3">
                        <p class="text-sm text-gray-600">Jenis Mutasi</p>
                        <p class="font-medium text-gray-900 mt-1">{{ ucfirst(str_replace('_', ' ', $mutasi->jenis_mutasi)) }}</p>
                    </div>
                    <div class="border-b pb-3">
                        <p class="text-sm text-gray-600">Tanggal Diajukan</p>
                        <p class="font-medium text-gray-900 mt-1">{{ $mutasi->tanggal_diajukan?->format('d/m/Y H:i') ?? '-' }}</p>
                    </div>
                    <div class="border-b pb-3">
                        <p class="text-sm text-gray-600">Disetujui Pada</p>
                        <p class="font-medium text-gray-900 mt-1">{{ $mutasi->tanggal_disetujui?->format('d/m/Y H:i') ?? '-' }}</p>
                    </div>
                    <div class="border-b pb-3">
                        <p class="text-sm text-gray-600">Disetujui Oleh</p>
                        <p class="font-medium text-gray-900 mt-1">{{ $mutasi->approver?->name ?? '-' }}</p>
                    </div>
                    <div class="pb-3">
                        <p class="text-sm text-gray-600">Tanggal Mulai</p>
                        <p class="font-medium text-gray-900 mt-1">{{ $mutasi->tanggal_mulai?->format('d/m/Y H:i') ?? '-' }}</p>
                    </div>
                    <div class="pb-3">
                        <p class="text-sm text-gray-600">Tanggal Selesai</p>
                        <p class="font-medium text-gray-900 mt-1">{{ $mutasi->tanggal_selesai?->format('d/m/Y H:i') ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Detail Mutasi -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Mutasi</h3>
                <div class="space-y-4">
                    @if($mutasi->jenis_mutasi === 'transfer_lokasi')
                        <div class="grid grid-cols-2 gap-4 pb-4 border-b">
                            <div>
                                <p class="text-sm text-gray-600">Lokasi Lama</p>
                                <p class="font-medium text-gray-900 mt-1">{{ $mutasi->lokasiLama?->nama_lokasi ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Lokasi Baru</p>
                                <p class="font-medium text-gray-900 mt-1">{{ $mutasi->lokasiBaru?->nama_lokasi ?? '-' }}</p>
                            </div>
                        </div>
                    @elseif($mutasi->jenis_mutasi === 'perubahan_kondisi')
                        <div class="pb-4 border-b">
                            <p class="text-sm text-gray-600">Kondisi Baru</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $mutasi->kondisi?->nama_kondisi ?? '-' }}</p>
                        </div>
                    @endif

                    @if($mutasi->alasan)
                        <div class="pb-4 border-b">
                            <p class="text-sm text-gray-600 mb-1">Alasan Mutasi</p>
                            <p class="text-gray-900">{{ $mutasi->alasan }}</p>
                        </div>
                    @endif

                    @if($mutasi->catatan)
                        <div class="pb-4 border-b">
                            <p class="text-sm text-gray-600 mb-1">Catatan</p>
                            <p class="text-gray-900">{{ $mutasi->catatan }}</p>
                        </div>
                    @endif

                    @if($mutasi->catatan_approval)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Catatan Approval</p>
                            <p class="text-gray-900">{{ $mutasi->catatan_approval }}</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Sidebar (1/3) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Aset Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Aset</h3>
                <div class="space-y-3 text-sm">
                    <div class="pb-3 border-b">
                        <p class="text-gray-600">Kode Aset</p>
                        <p class="font-medium text-gray-900 mt-1">{{ $mutasi->aset->kode_aset }}</p>
                    </div>
                    <div class="pb-3 border-b">
                        <p class="text-gray-600">Nama Aset</p>
                        <p class="font-medium text-gray-900 mt-1">{{ $mutasi->aset->nama_aset }}</p>
                    </div>
                    <div class="pb-3 border-b">
                        <p class="text-gray-600">Kategori</p>
                        <p class="font-medium text-gray-900 mt-1">{{ $mutasi->aset->kategori?->nama_kategori ?? '-' }}</p>
                    </div>
                    <div class="pb-3 border-b">
                        <p class="text-gray-600">Lokasi Saat Ini</p>
                        <p class="font-medium text-gray-900 mt-1">{{ $mutasi->aset->lokasi?->nama_lokasi ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Kondisi</p>
                        <p class="font-medium text-gray-900 mt-1">{{ $mutasi->aset->kondisi?->nama_kondisi ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Timeline</h3>
                <div class="space-y-4 text-sm">
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                            <div class="w-0.5 h-8 bg-gray-300 mt-1"></div>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Dibuat</p>
                            <p class="text-gray-600">{{ $mutasi->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @if($mutasi->tanggal_diajukan)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                                <div class="w-0.5 h-8 bg-gray-300 mt-1"></div>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Diajukan</p>
                                <p class="text-gray-600">{{ $mutasi->tanggal_diajukan->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($mutasi->tanggal_disetujui)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 bg-blue-400 rounded-full"></div>
                                <div class="w-0.5 h-8 bg-gray-300 mt-1"></div>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Disetujui</p>
                                <p class="text-gray-600">{{ $mutasi->tanggal_disetujui->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($mutasi->tanggal_mulai)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 bg-indigo-400 rounded-full"></div>
                                <div class="w-0.5 h-8 bg-gray-300 mt-1"></div>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Proses Dimulai</p>
                                <p class="text-gray-600">{{ $mutasi->tanggal_mulai->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($mutasi->tanggal_selesai)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 bg-green-600 rounded-full"></div>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Selesai</p>
                                <p class="text-gray-600">{{ $mutasi->tanggal_selesai->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div>
        <!-- Delete Modal -->
        <div @click.away="showDelete = false" x-show="showDelete" x-cloak class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus Mutasi Aset</h3>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus mutasi aset ini? Tindakan ini tidak dapat dibatalkan.</p>
                <form action="{{ route('transaksi.mutasi_aset.destroy', $mutasi->id) }}" method="POST" class="flex gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors">
                        Hapus
                    </button>
                    <button type="button" @click="showDelete = false" class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm font-medium transition-colors">
                        Batal
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
