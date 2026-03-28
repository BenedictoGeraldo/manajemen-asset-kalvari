@extends('layouts.main')

@section('title', 'Tambah Peminjaman Aset')
@section('page-title', 'Tambah Peminjaman Aset')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Form Pengajuan Peminjaman</h3>
        <p class="text-sm text-gray-600 mt-1">Isi data peminjaman aset sebelum diajukan untuk persetujuan.</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('transaksi.peminjaman.store') }}">
            @csrf
            @include('transaksi.peminjaman.form', ['submitLabel' => 'Simpan Pengajuan'])
        </form>
    </div>
</div>
@endsection
