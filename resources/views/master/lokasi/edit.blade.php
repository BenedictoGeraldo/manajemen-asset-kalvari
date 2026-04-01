@extends('layouts.main')

@section('title', 'Edit Lokasi')
@section('page-title', 'Edit Lokasi')

@section('content')
<div class="p-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-6">Edit Data Lokasi</h3>

            <form action="{{ route('master.lokasi.update', $lokasi->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    <!-- Nama Lokasi -->
                    <div>
                        <label for="nama_lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama_lokasi"
                               id="nama_lokasi"
                               value="{{ old('nama_lokasi', $lokasi->nama_lokasi) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_lokasi') border-red-500 @enderror">
                        @error('nama_lokasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gedung -->
                    <div>
                        <label for="gedung" class="block text-sm font-medium text-gray-700 mb-2">
                            Gedung <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="gedung"
                               id="gedung"
                               value="{{ old('gedung', $lokasi->gedung) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('gedung') border-red-500 @enderror">
                        @error('gedung')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lantai dan Ruangan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="lantai" class="block text-sm font-medium text-gray-700 mb-2">Lantai</label>
                            <input type="text"
                                   name="lantai"
                                   id="lantai"
                                   value="{{ old('lantai', $lokasi->lantai) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="ruangan" class="block text-sm font-medium text-gray-700 mb-2">Ruangan</label>
                            <input type="text"
                                   name="ruangan"
                                   id="ruangan"
                                   value="{{ old('ruangan', $lokasi->ruangan) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="keterangan_lokasi" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan_lokasi"
                                  id="keterangan_lokasi"
                                  rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('keterangan_lokasi', $lokasi->keterangan_lokasi) }}</textarea>
                    </div>

                    <!-- Status Aktif -->
                    <div>
                        <div class="flex items-center">
                            <input type="checkbox"
                                   name="is_active"
                                   id="is_active"
                                   value="1"
                                   {{ old('is_active', $lokasi->is_active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Status Aktif</label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex items-center space-x-3">
                    <button type="submit"
                            class="btn-a">
                        Perbarui Lokasi
                    </button>
                    <a href="{{ route('master.lokasi.index') }}"
                       data-navigate
                       class="btn-c">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

