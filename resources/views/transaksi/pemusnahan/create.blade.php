@extends('layouts.main')

@section('title', 'Catat Pemusnahan Aset')
@section('page-title', 'Tambah Pemusnahan Aset')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Form Pencatatan Pemusnahan Aset</h3>
        <p class="text-sm text-gray-600 mt-1">Catat pemusnahan aset dari inventaris.</p>
    </div>

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('transaksi.pemusnahan.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="aset_id" class="block text-sm font-medium text-gray-700 mb-1">Aset <span class="text-red-500">*</span></label>
                    <select id="aset_id" name="aset_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih Aset</option>
                        @foreach($asets as $aset)
                            <option value="{{ $aset->id }}" {{ old('aset_id') == $aset->id ? 'selected' : '' }}>
                                {{ $aset->kode_aset ?? '-' }} - {{ $aset->nama_aset }} (Stok: {{ $aset->jumlah_barang }})
                            </option>
                        @endforeach
                    </select>
                    @error('aset_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="tanggal_pemusnahan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pemusnahan <span class="text-red-500">*</span></label>
                    <input type="date" id="tanggal_pemusnahan" name="tanggal_pemusnahan" required
                           value="{{ old('tanggal_pemusnahan', date('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('tanggal_pemusnahan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="jumlah_dimusnahkan" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Dimusnahkan <span class="text-red-500">*</span></label>
                    <input type="number" id="jumlah_dimusnahkan" name="jumlah_dimusnahkan" min="1" required
                           value="{{ old('jumlah_dimusnahkan', 1) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('jumlah_dimusnahkan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="metode_pemusnahan" class="block text-sm font-medium text-gray-700 mb-1">Metode Pemusnahan <span class="text-red-500">*</span></label>
                    <select id="metode_pemusnahan" name="metode_pemusnahan" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih Metode</option>
                        @php $metodeList = ['Dihancurkan', 'Dibakar', 'Dibuang', 'Dijual', 'Dihibahkan']; @endphp
                        @foreach($metodeList as $metode)
                            <option value="{{ $metode }}" {{ old('metode_pemusnahan') == $metode ? 'selected' : '' }}>{{ $metode }}</option>
                        @endforeach
                    </select>
                    @error('metode_pemusnahan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="penanggung_jawab" class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab <span class="text-red-500">*</span></label>
                    <input type="text" id="penanggung_jawab" name="penanggung_jawab" required
                           value="{{ old('penanggung_jawab') }}"
                           placeholder="Contoh: Bpk. Andi (Koordinator Aset)"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('penanggung_jawab')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="nama_pengaju" class="block text-sm font-medium text-gray-700 mb-1">Nama Pengaju</label>
                    <input type="text" id="nama_pengaju" name="nama_pengaju"
                           value="{{ old('nama_pengaju', auth()->user()->name) }}"
                           readonly
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700">
                </div>

                <div>
                    <label for="unit_pengaju" class="block text-sm font-medium text-gray-700 mb-1">Unit / Departemen</label>
                    <input type="text" id="unit_pengaju" name="unit_pengaju"
                           value="{{ old('unit_pengaju', optional(auth()->user()->department)->name) }}"
                           readonly
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700">
                </div>

                <div class="md:col-span-2">
                    <label for="alasan_pemusnahan" class="block text-sm font-medium text-gray-700 mb-1">Alasan Pemusnahan <span class="text-red-500">*</span></label>
                    <input type="text" id="alasan_pemusnahan" name="alasan_pemusnahan" required
                           value="{{ old('alasan_pemusnahan') }}"
                           placeholder="Contoh: Barang rusak total, tidak bisa diperbaiki"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('alasan_pemusnahan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                    <textarea id="catatan" name="catatan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('catatan') }}</textarea>
                    @error('catatan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-6 flex items-center space-x-3">
                <button type="submit" class="btn-a-sm">
                    Simpan Transaksi
                </button>
                <a href="{{ route('transaksi.pemusnahan.index') }}" data-navigate class="btn-c-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
