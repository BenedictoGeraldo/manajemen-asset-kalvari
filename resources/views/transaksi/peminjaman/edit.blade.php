@extends('layouts.main')

@section('title', 'Edit Peminjaman Aset')
@section('page-title', 'Edit Peminjaman Aset')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Edit Pengajuan Peminjaman</h3>
        <p class="text-sm text-gray-600 mt-1">Nomor transaksi: {{ $peminjaman->nomor_peminjaman }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('transaksi.peminjaman.update', $peminjaman->id) }}">
            @csrf
            @method('PUT')
            @include('transaksi.peminjaman._form', ['submitLabel' => 'Simpan Perubahan'])
        </form>
    </div>
</div>
@endsection
