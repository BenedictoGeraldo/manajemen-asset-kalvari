@extends('layouts.main')

@section('title', 'Import Data Aset')
@section('page-title', 'Import Data Aset')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 content-fade-in">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Import Data Aset dari File
            </h2>
        </div>

        <div class="p-6">
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
            @endif
            @if(session('warning'))
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('warning') }}</div>
            @endif
            @if(session('import_errors'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                    <p class="font-semibold mb-1">Beberapa baris gagal diimpor:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach(session('import_errors') as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('data-aset.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih File <span class="text-red-500">*</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-indigo-400 transition-colors cursor-pointer" id="drop-zone">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-3 text-sm text-gray-600">
                            <span class="font-medium text-indigo-600">Klik untuk memilih file</span> atau drag & drop
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Format: .xlsx atau .csv (maks. 5MB)</p>
                        <input type="file" name="file" id="file-input" accept=".xlsx,.csv" required
                               class="hidden"
                               onchange="document.getElementById('file-name').textContent = this.files[0]?.name || ''">
                        <p id="file-name" class="mt-2 text-sm font-medium text-indigo-600"></p>
                    </div>
                    @error('file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('data-aset.index') }}" class="btn-c-outline">Batal</a>
                    <button type="submit" class="btn-import">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Format Data yang Diterima
            </h3>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-4">
                File Excel harus memiliki <strong>header (baris pertama)</strong> dengan nama kolom yang sesuai.
                Nilai untuk <code>kategori</code>, <code>lokasi</code>, <code>kondisi</code>, dan <code>pengelola</code>
                harus sesuai dengan data yang sudah ada di master data.
            </p>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="text-left px-3 py-2 font-semibold text-gray-600">Kolom</th>
                            <th class="text-left px-3 py-2 font-semibold text-gray-600">Wajib</th>
                            <th class="text-left px-3 py-2 font-semibold text-gray-600">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr><td class="px-3 py-2 font-mono text-xs">nama_aset</td><td class="px-3 py-2 text-green-600 font-bold">Ya</td><td class="px-3 py-2 text-gray-600">Nama barang/aset</td></tr>
                        <tr class="bg-gray-50"><td class="px-3 py-2 font-mono text-xs">kategori</td><td class="px-3 py-2 text-green-600 font-bold">Ya</td><td class="px-3 py-2 text-gray-600">Nama kategori (harus ada di master)</td></tr>
                        <tr><td class="px-3 py-2 font-mono text-xs">deskripsi_aset</td><td class="px-3 py-2 text-gray-400">Tidak</td><td class="px-3 py-2 text-gray-600">Deskripsi detail aset</td></tr>
                        <tr class="bg-gray-50"><td class="px-3 py-2 font-mono text-xs">ukuran</td><td class="px-3 py-2 text-gray-400">Tidak</td><td class="px-3 py-2 text-gray-600">Ukuran fisik aset</td></tr>
                        <tr><td class="px-3 py-2 font-mono text-xs">deskripsi_ukuran_bentuk</td><td class="px-3 py-2 text-gray-400">Tidak</td><td class="px-3 py-2 text-gray-600">Detail ukuran dan bentuk</td></tr>
                        <tr class="bg-gray-50"><td class="px-3 py-2 font-mono text-xs">lokasi</td><td class="px-3 py-2 text-green-600 font-bold">Ya</td><td class="px-3 py-2 text-gray-600">Nama lokasi (harus ada di master)</td></tr>
                        <tr><td class="px-3 py-2 font-mono text-xs">kegunaan</td><td class="px-3 py-2 text-gray-400">Tidak</td><td class="px-3 py-2 text-gray-600">Kegunaan aset</td></tr>
                        <tr class="bg-gray-50"><td class="px-3 py-2 font-mono text-xs">keterangan_kegunaan</td><td class="px-3 py-2 text-gray-400">Tidak</td><td class="px-3 py-2 text-gray-600">Keterangan tambahan</td></tr>
                        <tr><td class="px-3 py-2 font-mono text-xs">jumlah_barang</td><td class="px-3 py-2 text-gray-400">Tidak</td><td class="px-3 py-2 text-gray-600">Angka (default: 1)</td></tr>
                        <tr class="bg-gray-50"><td class="px-3 py-2 font-mono text-xs">tipe_grup</td><td class="px-3 py-2 text-gray-400">Tidak</td><td class="px-3 py-2 text-gray-600">individual / set / grup</td></tr>
                        <tr><td class="px-3 py-2 font-mono text-xs">keterangan_tipe_grup</td><td class="px-3 py-2 text-gray-400">Tidak</td><td class="px-3 py-2 text-gray-600">Detail tipe grup</td></tr>
                        <tr class="bg-gray-50"><td class="px-3 py-2 font-mono text-xs">nilai_budget</td><td class="px-3 py-2 text-gray-400">Tidak</td><td class="px-3 py-2 text-gray-600">Angka (desimal)</td></tr>
                        <tr><td class="px-3 py-2 font-mono text-xs">sumber_dana</td><td class="px-3 py-2 text-gray-400">Tidak</td><td class="px-3 py-2 text-gray-600">Sumber pendanaan</td></tr>
                        <tr class="bg-gray-50"><td class="px-3 py-2 font-mono text-xs">keterangan_budget</td><td class="px-3 py-2 text-gray-400">Tidak</td><td class="px-3 py-2 text-gray-600">Catatan budget</td></tr>
                        <tr><td class="px-3 py-2 font-mono text-xs">pengelola</td><td class="px-3 py-2 text-green-600 font-bold">Ya</td><td class="px-3 py-2 text-gray-600">Nama pengelola (harus ada di master)</td></tr>
                        <tr class="bg-gray-50"><td class="px-3 py-2 font-mono text-xs">tahun_pengadaan</td><td class="px-3 py-2 text-gray-400">Tidak</td><td class="px-3 py-2 text-gray-600">Tahun (default: tahun ini)</td></tr>
                        <tr><td class="px-3 py-2 font-mono text-xs">nilai_pengadaan_total</td><td class="px-3 py-2 text-gray-400">Tidak</td><td class="px-3 py-2 text-gray-600">Angka (desimal)</td></tr>
                        <tr class="bg-gray-50"><td class="px-3 py-2 font-mono text-xs">nilai_pengadaan_per_unit</td><td class="px-3 py-2 text-gray-400">Tidak</td><td class="px-3 py-2 text-gray-600">Angka (desimal)</td></tr>
                        <tr><td class="px-3 py-2 font-mono text-xs">kondisi</td><td class="px-3 py-2 text-green-600 font-bold">Ya</td><td class="px-3 py-2 text-gray-600">Nama kondisi (harus ada di master)</td></tr>
                        <tr class="bg-gray-50"><td class="px-3 py-2 font-mono text-xs">catatan</td><td class="px-3 py-2 text-gray-400">Tidak</td><td class="px-3 py-2 text-gray-600">Catatan tambahan</td></tr>
                        <tr><td class="px-3 py-2 font-mono text-xs">departemen</td><td class="px-3 py-2 text-gray-400">Tidak</td><td class="px-3 py-2 text-gray-600">Nama departemen (jika ada)</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('data-aset.template') }}" class="btn-export-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download Template Excel
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');

    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            document.getElementById('file-name').textContent = e.dataTransfer.files[0].name;
        }
    });
});
</script>
@endpush
@endsection
