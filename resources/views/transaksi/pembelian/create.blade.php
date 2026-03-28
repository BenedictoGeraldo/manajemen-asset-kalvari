@extends('layouts.main')

@section('title', 'Tambah Pembelian')
@section('page-title', 'Tambah Pembelian')

@section('content')
<div class="p-6">
    <div class="max-w-7xl">
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('transaksi.pembelian.store') }}" method="POST">
                @csrf
                @include('transaksi.pembelian.form', ['submitLabel' => 'Simpan'])
            </form>
        </div>
    </div>
</div>
@endsection
