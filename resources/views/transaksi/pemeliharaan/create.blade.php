@extends('layouts.main')

@section('title', 'Tambah Pemeliharaan Aset')
@section('page-title', 'Tambah Pemeliharaan Aset')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Form Pengajuan Pemeliharaan</h3>
        <p class="text-sm text-gray-600 mt-1">Isi data pemeliharaan aset sebelum diajukan untuk persetujuan.</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('transaksi.pemeliharaan.store') }}">
            @csrf
            @include('transaksi.pemeliharaan._form', ['submitLabel' => 'Simpan Pengajuan'])
        </form>
    </div>
</div>
@endsection
