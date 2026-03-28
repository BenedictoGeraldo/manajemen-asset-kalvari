@extends('layouts.main')

@section('title', 'Edit Pembelian')
@section('page-title', 'Edit Pembelian')

@section('content')
<div class="p-6">
    <div class="max-w-7xl">
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('transaksi.pembelian.update', $pembelian->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('transaksi.pembelian.form', ['pembelian' => $pembelian, 'submitLabel' => 'Perbarui'])
            </form>
        </div>
    </div>
</div>
@endsection
