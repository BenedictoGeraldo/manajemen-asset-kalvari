@extends('layouts.main')

@section('title', 'Edit Mutasi Aset')
@section('page-title', 'Edit Mutasi Aset')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Form Edit Mutasi Aset</h3>
        <p class="text-sm text-gray-600 mt-1">
            <span class="font-medium">Nomor:</span> {{ $mutasi->nomor_mutasi }}
        </p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('transaksi.mutasi_aset.update', $mutasi->id) }}">
            @csrf
            @method('PUT')
            @include('transaksi.mutasi_aset.form', ['mutasi' => $mutasi, 'submitLabel' => 'Simpan Perubahan'])
        </form>
    </div>
</div>
@endsection
