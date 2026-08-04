@php
    $submitLabel = $submitLabel ?? 'Simpan';
    $selectedAsetId = old('data_aset_kolektif_id', $pemeliharaan->data_aset_kolektif_id ?? '');
    $selectedAset = $asets->firstWhere('id', (int) $selectedAsetId);
@endphp

@if(session('error'))
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
        {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="data_aset_kolektif_id" class="block text-sm font-medium text-gray-700 mb-1">Aset <span class="text-red-500">*</span></label>
        <select id="data_aset_kolektif_id" name="data_aset_kolektif_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="">Pilih Aset</option>
            @foreach($asets as $aset)
                <option value="{{ $aset->id }}" {{ (string) $selectedAsetId === (string) $aset->id ? 'selected' : '' }}>
                    {{ $aset->kode_aset ?: '-' }} - {{ $aset->nama_aset }}
                </option>
            @endforeach
        </select>
        @error('data_aset_kolektif_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="tanggal_pengajuan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengajuan <span class="text-red-500">*</span></label>
        <input type="date" id="tanggal_pengajuan" name="tanggal_pengajuan" required
               value="{{ old('tanggal_pengajuan', isset($pemeliharaan) ? optional($pemeliharaan->tanggal_pengajuan)->format('Y-m-d') : date('Y-m-d')) }}"
             class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        @error('tanggal_pengajuan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="tanggal_rencana" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Rencana</label>
        <input type="date" id="tanggal_rencana" name="tanggal_rencana"
               value="{{ old('tanggal_rencana', isset($pemeliharaan) ? optional($pemeliharaan->tanggal_rencana)->format('Y-m-d') : '') }}"
             class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        @error('tanggal_rencana')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="nama_pengaju" class="block text-sm font-medium text-gray-700 mb-1">Nama Pengaju</label>
        <input type="text" id="nama_pengaju" name="nama_pengaju"
               value="{{ old('nama_pengaju', $pemeliharaan->nama_pengaju ?? (isset($pemeliharaan) ? '' : auth()->user()->name)) }}"
               readonly
               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700">
    </div>

    <div>
        <label for="unit_pengaju" class="block text-sm font-medium text-gray-700 mb-1">Unit / Departemen</label>
        <input type="text" id="unit_pengaju" name="unit_pengaju"
               value="{{ old('unit_pengaju', $pemeliharaan->unit_pengaju ?? (isset($pemeliharaan) ? '' : optional(auth()->user()->department)->name)) }}"
               readonly
               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700">
    </div>

    <div>
        <label for="jenis_pemeliharaan" class="block text-sm font-medium text-gray-700 mb-1">Jenis Pemeliharaan <span class="text-red-500">*</span></label>
        @php $selectedJenis = old('jenis_pemeliharaan', $pemeliharaan->jenis_pemeliharaan ?? 'rutin'); @endphp
        <select id="jenis_pemeliharaan" name="jenis_pemeliharaan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="rutin" {{ $selectedJenis === 'rutin' ? 'selected' : '' }}>Rutin</option>
            <option value="perbaikan" {{ $selectedJenis === 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
            <option value="darurat" {{ $selectedJenis === 'darurat' ? 'selected' : '' }}>Darurat</option>
        </select>
        @error('jenis_pemeliharaan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="prioritas" class="block text-sm font-medium text-gray-700 mb-1">Prioritas <span class="text-red-500">*</span></label>
        @php $selectedPrioritas = old('prioritas', $pemeliharaan->prioritas ?? 'sedang'); @endphp
        <select id="prioritas" name="prioritas" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="rendah" {{ $selectedPrioritas === 'rendah' ? 'selected' : '' }}>Rendah</option>
            <option value="sedang" {{ $selectedPrioritas === 'sedang' ? 'selected' : '' }}>Sedang</option>
            <option value="tinggi" {{ $selectedPrioritas === 'tinggi' ? 'selected' : '' }}>Tinggi</option>
        </select>
        @error('prioritas')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="vendor_nama" class="block text-sm font-medium text-gray-700 mb-1">Vendor / Teknisi</label>
        <input type="text" id="vendor_nama" name="vendor_nama" value="{{ old('vendor_nama', $pemeliharaan->vendor_nama ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>

    <div>
        <label for="vendor_kontak" class="block text-sm font-medium text-gray-700 mb-1">Kontak Vendor</label>
        <input type="text" id="vendor_kontak" name="vendor_kontak" value="{{ old('vendor_kontak', $pemeliharaan->vendor_kontak ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>

    <div>
        <label for="estimasi_biaya" class="block text-sm font-medium text-gray-700 mb-1">Estimasi Biaya</label>
        <input type="number" id="estimasi_biaya" name="estimasi_biaya" min="0" step="1000" value="{{ old('estimasi_biaya', $pemeliharaan->estimasi_biaya ?? 0) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>

    <div>
        <label for="realisasi_biaya" class="block text-sm font-medium text-gray-700 mb-1">Realisasi Biaya</label>
        <input type="number" id="realisasi_biaya" name="realisasi_biaya" min="0" step="1000" value="{{ old('realisasi_biaya', $pemeliharaan->realisasi_biaya ?? 0) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>

    <div class="md:col-span-2">
        <label for="keluhan" class="block text-sm font-medium text-gray-700 mb-1">Keluhan / Tujuan Pemeliharaan</label>
        <textarea id="keluhan" name="keluhan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('keluhan', $pemeliharaan->keluhan ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label for="tindakan" class="block text-sm font-medium text-gray-700 mb-1">Tindakan</label>
        <textarea id="tindakan" name="tindakan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('tindakan', $pemeliharaan->tindakan ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
        <textarea id="catatan" name="catatan" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('catatan', $pemeliharaan->catatan ?? '') }}</textarea>
    </div>
</div>

@if(!isset($pemeliharaan))
<input type="hidden" name="status" value="diajukan">
@endif

<div class="mt-6 flex items-center space-x-3">
    <button type="submit" class="btn-a-sm">
        {{ $submitLabel }}
    </button>
    <a href="{{ route('transaksi.pemeliharaan.index') }}" data-navigate class="btn-c-sm">
        Batal
    </a>
</div>

