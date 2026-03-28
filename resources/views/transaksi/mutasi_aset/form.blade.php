@php
    $mutasi = $mutasi ?? null;

    $jenisOptions = [
        'transfer_lokasi' => 'Transfer Lokasi',
        'perubahan_kondisi' => 'Perubahan Kondisi',
        'write_off' => 'Write Off',
        'penghapusan' => 'Penghapusan',
    ];

    $statusOptions = [
        'draft' => 'Draft',
        'diajukan' => 'Diajukan',
        'dibatalkan' => 'Dibatalkan',
    ];

    $submitLabel = $submitLabel ?? 'Simpan Pengajuan';
@endphp

<div x-data="{ jenisSelected: '{{ old('jenis_mutasi', isset($mutasi) ? $mutasi->jenis_mutasi : '') }}' }" class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Aset -->
        <div>
            <label for="data_aset_kolektif_id" class="block text-sm font-medium text-gray-700 mb-1">
                Aset <span class="text-red-500">*</span>
            </label>
            <select id="data_aset_kolektif_id" name="data_aset_kolektif_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 appearance-none bg-white cursor-pointer" required>
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
            <input type="date" id="tanggal_mutasi" name="tanggal_mutasi" value="{{ old('tanggal_mutasi', isset($mutasi) ? $mutasi->tanggal_mutasi?->format('Y-m-d') : now()->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" required>
            @error('tanggal_mutasi')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Jenis Mutasi -->
        <div>
            <label for="jenis_mutasi" class="block text-sm font-medium text-gray-700 mb-1">
                Jenis Mutasi <span class="text-red-500">*</span>
            </label>
            <select id="jenis_mutasi" name="jenis_mutasi" x-model="jenisSelected" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 appearance-none bg-white cursor-pointer" required>
                <option value="">-- Pilih Jenis Mutasi --</option>
                @foreach($jenisOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('jenis_mutasi', isset($mutasi) ? $mutasi->jenis_mutasi : null) == $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('jenis_mutasi')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                Status <span class="text-red-500">*</span>
            </label>
            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 appearance-none bg-white cursor-pointer" required>
                <option value="">-- Pilih Status --</option>
                @foreach($statusOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', isset($mutasi) ? $mutasi->status : 'draft') == $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Lokasi Baru (conditional) -->
        <div x-show="jenisSelected === 'transfer_lokasi'">
            <label for="lokasi_baru_id" class="block text-sm font-medium text-gray-700 mb-1">
                Lokasi Baru
            </label>
            <select id="lokasi_baru_id" name="lokasi_baru_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 appearance-none bg-white cursor-pointer">
                <option value="">-- Pilih Lokasi --</option>
                @foreach($lokasis as $lokasi)
                    <option value="{{ $lokasi->id }}" @selected(old('lokasi_baru_id', isset($mutasi) ? $mutasi->lokasi_baru_id : null) == $lokasi->id)>
                        {{ $lokasi->nama_lokasi }}
                    </option>
                @endforeach
            </select>
            @error('lokasi_baru_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Kondisi (conditional) -->
        <div x-show="jenisSelected === 'perubahan_kondisi'">
            <label for="kondisi_id" class="block text-sm font-medium text-gray-700 mb-1">
                Kondisi
            </label>
            <select id="kondisi_id" name="kondisi_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 appearance-none bg-white cursor-pointer">
                <option value="">-- Pilih Kondisi --</option>
                @foreach($kondisis as $kondisi)
                    <option value="{{ $kondisi->id }}" @selected(old('kondisi_id', isset($mutasi) ? $mutasi->kondisi_id : null) == $kondisi->id)>
                        {{ $kondisi->nama_kondisi }}
                    </option>
                @endforeach
            </select>
            @error('kondisi_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Alasan -->
        <div class="md:col-span-2">
            <label for="alasan" class="block text-sm font-medium text-gray-700 mb-1">
                Alasan Mutasi
            </label>
            <textarea id="alasan" name="alasan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" placeholder="Jelaskan alasan mutasi aset ini">{{ old('alasan', isset($mutasi) ? $mutasi->alasan : '') }}</textarea>
            @error('alasan')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Catatan -->
        <div class="md:col-span-2">
            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">
                Catatan
            </label>
            <textarea id="catatan" name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" placeholder="Catatan tambahan jika ada">{{ old('catatan', isset($mutasi) ? $mutasi->catatan : '') }}</textarea>
            @error('catatan')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Buttons -->
    <div class="flex gap-3 pt-4 border-t">
        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-800 text-white text-sm font-medium rounded-lg transition-colors">
            {{ $submitLabel }}
        </button>
        <a href="{{ route('transaksi.mutasi_aset.index') }}" data-navigate class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-lg transition-colors">
            Batal
        </a>
    </div>
</div>
