@extends('layouts.main')

@section('title', 'Tambah Mutasi Aset')
@section('page-title', 'Tambah Mutasi Aset')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Form Pengajuan Mutasi Aset</h3>
        <p class="text-sm text-gray-600 mt-1">Isi data mutasi aset sebelum diajukan untuk persetujuan.</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('transaksi.mutasi_aset.store') }}">
            @csrf
            @include('transaksi.mutasi_aset.form', ['submitLabel' => 'Simpan Pengajuan'])
        </form>
    </div>
</div>
@endsection
