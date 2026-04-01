@extends('layouts.main')

@section('title', 'Tambah Lokasi')
@section('page-title', 'Tambah Lokasi')

@section('content')
<div class="p-6">
    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('master.lokasi.store') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="nama_lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_lokasi" id="nama_lokasi" required value="{{ old('nama_lokasi') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_lokasi') border-red-500 @enderror">
                        @error('nama_lokasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gedung" class="block text-sm font-medium text-gray-700 mb-2">
                            Gedung <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="gedung" id="gedung" required value="{{ old('gedung') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gedung') border-red-500 @enderror">
                        @error('gedung')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="lantai" class="block text-sm font-medium text-gray-700 mb-2">Lantai</label>
                            <input type="text" name="lantai" id="lantai" value="{{ old('lantai') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="ruangan" class="block text-sm font-medium text-gray-700 mb-2">Ruangan</label>
                            <input type="text" name="ruangan" id="ruangan" value="{{ old('ruangan') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label for="keterangan_lokasi" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan_lokasi" id="keterangan_lokasi" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('keterangan_lokasi') }}</textarea>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">Aktif</label>
                    </div>
                </div>

                <div class="mt-6 flex items-center space-x-3">
                    <button type="submit"
                            class="btn-a-sm">
                        Simpan
                    </button>
                    <a href="{{ route('master.lokasi.index') }}" data-navigate
                       class="btn-c-sm">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

