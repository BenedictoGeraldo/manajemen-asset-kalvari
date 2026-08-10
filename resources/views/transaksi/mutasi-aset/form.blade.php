@php
    $mutasi = $mutasi ?? null;
    $submitLabel = $submitLabel ?? 'Simpan Pengajuan';
@endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Aset -->
        <div>
            <label for="data_aset_kolektif_id" class="block text-sm font-medium text-gray-700 mb-1">
                Aset <span class="text-red-500">*</span>
            </label>
            <select id="data_aset_kolektif_id" name="data_aset_kolektif_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 appearance-none bg-white cursor-pointer" required>
                <option value="">-- Pilih Aset --</option>
                @foreach($asets as $aset)
                    <option value="{{ $aset->id }}" @selected(old('data_aset_kolektif_id', isset($mutasi) ? $mutasi->data_aset_kolektif_id : null) == $aset->id)>
                        {{ $aset->kode_aset }} - {{ $aset->nama_aset }}
                    </option>
                @endforeach
            </select>
            @error('data_aset_kolektif_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tanggal Mutasi -->
        <div>
            <label for="tanggal_mutasi" class="block text-sm font-medium text-gray-700 mb-1">
                Tanggal Mutasi <span class="text-red-500">*</span>
            </label>
            <input type="date" id="tanggal_mutasi" name="tanggal_mutasi" value="{{ old('tanggal_mutasi', isset($mutasi) ? $mutasi->tanggal_mutasi?->format('Y-m-d') : now()->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" required>
            @error('tanggal_mutasi')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nama Pengaju (readonly) -->
        <div>
            <label for="nama_pengaju" class="block text-sm font-medium text-gray-700 mb-1">
                Nama Pengaju
            </label>
            <input type="text" id="nama_pengaju" name="nama_pengaju"
                   value="{{ old('nama_pengaju', isset($mutasi) ? $mutasi->nama_pengaju : auth()->user()->name) }}"
                   readonly
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700">
        </div>

        <!-- Unit Pengaju (readonly) -->
        <div>
            <label for="unit_pengaju" class="block text-sm font-medium text-gray-700 mb-1">
                Unit / Departemen
            </label>
            <input type="text" id="unit_pengaju" name="unit_pengaju"
                   value="{{ old('unit_pengaju', isset($mutasi) ? $mutasi->unit_pengaju : optional(auth()->user()->department)->name) }}"
                   readonly
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700">
        </div>

        <!-- Lokasi Baru -->
        <div>
            <label for="lokasi_baru_id" class="block text-sm font-medium text-gray-700 mb-1">
                Lokasi Baru <span class="text-red-500">*</span>
            </label>
            <select id="lokasi_baru_id" name="lokasi_baru_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 appearance-none bg-white cursor-pointer">
                <option value="">-- Pilih Lokasi --</option>
                @foreach($lokasiGrouped as $group)
                    <optgroup label="{{ $group['group'] }}">
                        @foreach($group['items'] as $item)
                            <option value="{{ $item['id'] }}" @selected(old('lokasi_baru_id', isset($mutasi) ? $mutasi->lokasi_baru_id : null) == $item['id'])>
                                {{ $item['label'] }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            @error('lokasi_baru_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Alasan -->
        <div class="md:col-span-2">
            <label for="alasan" class="block text-sm font-medium text-gray-700 mb-1">
                Alasan Mutasi
            </label>
            <textarea id="alasan" name="alasan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" placeholder="Jelaskan alasan mutasi aset ini">{{ old('alasan', isset($mutasi) ? $mutasi->alasan : '') }}</textarea>
            @error('alasan')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Catatan -->
        <div class="md:col-span-2">
            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">
                Catatan
            </label>
            <textarea id="catatan" name="catatan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" placeholder="Catatan tambahan jika ada">{{ old('catatan', isset($mutasi) ? $mutasi->catatan : '') }}</textarea>
            @error('catatan')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <input type="hidden" name="jenis_mutasi" value="transfer_lokasi">
    @if(!isset($mutasi))
    <input type="hidden" name="status" value="diajukan">
    @endif

    <!-- Buttons -->
    <div class="flex gap-3 pt-4 border-t">
        <button type="submit" class="btn-a-sm">
            {{ $submitLabel }}
        </button>
        <a href="{{ route('transaksi.mutasi_aset.index') }}" data-navigate class="btn-c-sm">
            Batal
        </a>
    </div>
</div>
