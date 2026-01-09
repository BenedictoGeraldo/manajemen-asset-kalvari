@extends('layouts.main')

@section('title', 'Edit Kondisi')
@section('page-title', 'Edit Kondisi')

@section('content')
<div class="p-6">
    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('master.kondisi.update', $kondisi->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="nama_kondisi" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kondisi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_kondisi" id="nama_kondisi" required value="{{ old('nama_kondisi', $kondisi->nama_kondisi) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="kode_warna" class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Warna <span class="text-red-500">*</span>
                            </label>
                            <select name="kode_warna" id="kode_warna" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="green" {{ $kondisi->kode_warna == 'green' ? 'selected' : '' }}>Green (Hijau)</option>
                                <option value="blue" {{ $kondisi->kode_warna == 'blue' ? 'selected' : '' }}>Blue (Biru)</option>
                                <option value="yellow" {{ $kondisi->kode_warna == 'yellow' ? 'selected' : '' }}>Yellow (Kuning)</option>
                                <option value="orange" {{ $kondisi->kode_warna == 'orange' ? 'selected' : '' }}>Orange (Oranye)</option>
                                <option value="red" {{ $kondisi->kode_warna == 'red' ? 'selected' : '' }}>Red (Merah)</option>
                            </select>
                        </div>
                        <div>
                            <label for="urutan" class="block text-sm font-medium text-gray-700 mb-2">
                                Urutan <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="urutan" id="urutan" required value="{{ old('urutan', $kondisi->urutan) }}" min="1"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('keterangan', $kondisi->keterangan) }}</textarea>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $kondisi->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">Aktif</label>
                    </div>
                </div>
                <div class="mt-6 flex items-center space-x-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                        Perbarui
                    </button>
                    <a href="{{ route('master.kondisi.index') }}" data-navigate
                       class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-150">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
