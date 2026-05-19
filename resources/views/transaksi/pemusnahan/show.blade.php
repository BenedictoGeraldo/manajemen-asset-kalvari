@extends('layouts.main')

@section('title', 'Detail Pemusnahan Aset')
@section('page-title', 'Detail Pemusnahan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 content-fade-in">
    <div class="flex justify-between items-center">
        <a href="{{ route('transaksi.pemusnahan.index') }}" class="btn-c-sm flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="bg-red-600 px-6 py-4">
            <h3 class="text-lg font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Detail Pemusnahan Aset - {{ $pemusnahan->kode_transaksi }}
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Informasi Aset</h4>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500">Nama Aset</p>
                            <p class="text-base font-medium text-gray-900">{{ $pemusnahan->aset->nama_aset }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Kode Aset</p>
                            <p class="text-sm text-gray-900">{{ $pemusnahan->aset->kode_aset }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Jumlah Dimusnahkan</p>
                            <p class="text-sm text-gray-900 font-bold text-red-600">{{ $pemusnahan->jumlah_dimusnahkan }} unit</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Detail Transaksi</h4>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500">Tanggal Pemusnahan</p>
                            <p class="text-sm text-gray-900">{{ $pemusnahan->tanggal_pemusnahan->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Metode Pemusnahan</p>
                            <p class="text-sm text-gray-900 bg-gray-100 inline-block px-2 py-1 rounded">{{ $pemusnahan->metode_pemusnahan }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Penanggung Jawab</p>
                            <p class="text-sm text-gray-900">{{ $pemusnahan->penanggung_jawab }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Alasan & Catatan</h4>
                    <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                        <div>
                            <p class="text-xs text-gray-500">Alasan Pemusnahan</p>
                            <p class="text-sm text-gray-900 mt-1 italic">"{{ $pemusnahan->alasan_pemusnahan }}"</p>
                        </div>
                        @if($pemusnahan->catatan)
                        <div>
                            <p class="text-xs text-gray-500">Catatan Tambahan</p>
                            <p class="text-sm text-gray-900 mt-1">{{ $pemusnahan->catatan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4 flex justify-end">
            <button onclick="window.print()" class="btn-c-sm flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Bukti Pemusnahan
            </button>
        </div>
    </div>
</div>
@endsection
