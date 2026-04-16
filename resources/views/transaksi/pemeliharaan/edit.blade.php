@extends('layouts.main')

@section('title', 'Edit Pemeliharaan Aset')
@section('page-title', 'Edit Pemeliharaan Aset')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Edit Pengajuan Pemeliharaan</h3>
        <p class="text-sm text-gray-600 mt-1">Nomor transaksi: {{ $pemeliharaan->nomor_pemeliharaan }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('transaksi.pemeliharaan.update', $pemeliharaan->id) }}">
            @csrf
            @method('PUT')
            @include('transaksi.pemeliharaan.form', ['submitLabel' => 'Simpan Perubahan'])
        </form>
    </div>
</div>
@endsection
