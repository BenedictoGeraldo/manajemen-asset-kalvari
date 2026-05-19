@extends('layouts.main')

@section('title', 'Catat Pemusnahan Aset')
@section('page-title', 'Tambah Pemusnahan')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6 content-fade-in">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Catat Pemusnahan Aset</h2>
        <p class="text-gray-600">Formulir untuk mencatat penghapusan aset dari inventaris.</p>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('transaksi.pemusnahan.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-1 md:col-span-2">
                <label for="aset_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Aset <span class="text-red-500">*</span></label>
                <select name="aset_id" id="aset_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">-- Pilih Aset --</option>
                    @foreach($asets as $aset)
                        <option value="{{ $aset->id }}" {{ old('aset_id') == $aset->id ? 'selected' : '' }}>
                            {{ $aset->nama_aset }} ({{ $aset->kode_aset }}) - Stok: {{ $aset->jumlah_barang }}
                        </option>
                    @endforeach
                </select>
                @error('aset_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="jumlah_dimusnahkan" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Dimusnahkan <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah_dimusnahkan" id="jumlah_dimusnahkan" min="1" value="{{ old('jumlah_dimusnahkan', 1) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                @error('jumlah_dimusnahkan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="tanggal_pemusnahan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pemusnahan <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_pemusnahan" id="tanggal_pemusnahan" value="{{ old('tanggal_pemusnahan', date('Y-m-d')) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                @error('tanggal_pemusnahan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="metode_pemusnahan" class="block text-sm font-medium text-gray-700 mb-1">Metode Pemusnahan <span class="text-red-500">*</span></label>
                <select name="metode_pemusnahan" id="metode_pemusnahan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="Dihancurkan" {{ old('metode_pemusnahan') == 'Dihancurkan' ? 'selected' : '' }}>Dihancurkan</option>
                    <option value="Dibakar" {{ old('metode_pemusnahan') == 'Dibakar' ? 'selected' : '' }}>Dibakar</option>
                    <option value="Dibuang" {{ old('metode_pemusnahan') == 'Dibuang' ? 'selected' : '' }}>Dibuang</option>
                    <option value="Dijual" {{ old('metode_pemusnahan') == 'Dijual' ? 'selected' : '' }}>Dijual/Lelang</option>
                    <option value="Dihibahkan" {{ old('metode_pemusnahan') == 'Dihibahkan' ? 'selected' : '' }}>Dihibahkan</option>
                </select>
                @error('metode_pemusnahan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="penanggung_jawab" class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab <span class="text-red-500">*</span></label>
                <input type="text" name="penanggung_jawab" id="penanggung_jawab" value="{{ old('penanggung_jawab') }}" placeholder="Contoh: Bpk. Andi (Koordinator Aset)"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                @error('penanggung_jawab') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-1 md:col-span-2">
                <label for="alasan_pemusnahan" class="block text-sm font-medium text-gray-700 mb-1">Alasan Pemusnahan <span class="text-red-500">*</span></label>
                <input type="text" name="alasan_pemusnahan" id="alasan_pemusnahan" value="{{ old('alasan_pemusnahan') }}" placeholder="Contoh: Barang rusak total/tidak bisa diperbaiki"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                @error('alasan_pemusnahan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-1 md:col-span-2">
                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                <textarea name="catatan" id="catatan" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('catatan') }}</textarea>
                @error('catatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-3">
            <a href="{{ route('transaksi.pemusnahan.index') }}" class="btn-c-sm">Batal</a>
            <button type="submit" class="btn-a-sm">Simpan Transaksi</button>
        </div>
    </form>
</div>
@endsection
