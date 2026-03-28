@php
    $submitLabel = $submitLabel ?? 'Simpan';

    $existingItems = isset($peminjaman)
        ? $peminjaman->items->map(function ($item) {
            return [
                'data_aset_kolektif_id' => $item->data_aset_kolektif_id,
                'jumlah' => $item->jumlah,
                'catatan_item' => $item->catatan_item,
            ];
        })->toArray()
        : [[
            'data_aset_kolektif_id' => '',
            'jumlah' => 1,
            'catatan_item' => '',
        ]];

    $itemsState = old('items', $existingItems);
@endphp

@if(session('error'))
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
        {{ session('error') }}
    </div>
@endif

<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="tanggal_pengajuan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengajuan <span class="text-red-500">*</span></label>
            <input type="date" id="tanggal_pengajuan" name="tanggal_pengajuan" required
                   value="{{ old('tanggal_pengajuan', isset($peminjaman) ? optional($peminjaman->tanggal_pengajuan)->format('Y-m-d') : date('Y-m-d')) }}"
                   class="w-full rounded-lg @error('tanggal_pengajuan') border-red-500 @else border-gray-300 @enderror focus:border-blue-500 focus:ring-blue-500">
            @error('tanggal_pengajuan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="tanggal_rencana_kembali" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Rencana Kembali</label>
            <input type="date" id="tanggal_rencana_kembali" name="tanggal_rencana_kembali"
                   value="{{ old('tanggal_rencana_kembali', isset($peminjaman) ? optional($peminjaman->tanggal_rencana_kembali)->format('Y-m-d') : '') }}"
                   class="w-full rounded-lg @error('tanggal_rencana_kembali') border-red-500 @else border-gray-300 @enderror focus:border-blue-500 focus:ring-blue-500">
            @error('tanggal_rencana_kembali')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="nama_peminjam" class="block text-sm font-medium text-gray-700 mb-1">Nama Peminjam <span class="text-red-500">*</span></label>
            <input type="text" id="nama_peminjam" name="nama_peminjam" required value="{{ old('nama_peminjam', $peminjaman->nama_peminjam ?? '') }}"
                   class="w-full rounded-lg @error('nama_peminjam') border-red-500 @else border-gray-300 @enderror focus:border-blue-500 focus:ring-blue-500">
            @error('nama_peminjam')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="kontak_peminjam" class="block text-sm font-medium text-gray-700 mb-1">Kontak Peminjam</label>
            <input type="text" id="kontak_peminjam" name="kontak_peminjam" value="{{ old('kontak_peminjam', $peminjaman->kontak_peminjam ?? '') }}"
                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="unit_peminjam" class="block text-sm font-medium text-gray-700 mb-1">Unit / Departemen</label>
            <input type="text" id="unit_peminjam" name="unit_peminjam" value="{{ old('unit_peminjam', $peminjaman->unit_peminjam ?? '') }}"
                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
            @php $selectedStatus = old('status', $peminjaman->status ?? 'draft'); @endphp
            <select id="status" name="status" class="w-full rounded-lg @error('status') border-red-500 @else border-gray-300 @enderror focus:border-blue-500 focus:ring-blue-500">
                <option value="draft" {{ $selectedStatus === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="diajukan" {{ $selectedStatus === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                <option value="dibatalkan" {{ $selectedStatus === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="md:col-span-2">
            <label for="keperluan" class="block text-sm font-medium text-gray-700 mb-1">Keperluan</label>
            <textarea id="keperluan" name="keperluan" rows="2" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('keperluan', $peminjaman->keperluan ?? '') }}</textarea>
        </div>

        <div class="md:col-span-2">
            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
            <textarea id="catatan" name="catatan" rows="2" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('catatan', $peminjaman->catatan ?? '') }}</textarea>
        </div>
    </div>
</div>

<div class="mt-8 border-t pt-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Item Aset Dipinjam</h3>
        <button type="button" id="btn-add-item" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">Tambah Item</button>
    </div>

    @error('items')<p class="mb-3 text-sm text-red-600">{{ $message }}</p>@enderror
    <div id="items-container" class="space-y-4"></div>
</div>

<div class="mt-6 flex items-center space-x-3">
    <button type="submit"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
        {{ $submitLabel }}
    </button>
    <a href="{{ route('transaksi.peminjaman.index') }}" data-navigate
       class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
        Batal
    </a>
</div>

<script>
    const itemsContainer = document.getElementById('items-container');
    const addItemButton = document.getElementById('btn-add-item');
    const initialItems = @json($itemsState);

    const asetOptions = [{ id: '', label: 'Pilih Aset' }, ...@json($asets->map(fn($x) => ['id' => $x->id, 'label' => ($x->kode_aset ?? '-') . ' - ' . $x->nama_aset . ' (Stok: ' . $x->jumlah_barang . ')'])->values())];

    function buildOptions(list, selected) {
        return list.map(option => {
            const isSelected = String(option.id) === String(selected) ? 'selected' : '';
            return `<option value="${option.id}" ${isSelected}>${option.label}</option>`;
        }).join('');
    }

    function renderItem(item, index) {
        return `
        <div class="border border-gray-200 rounded-lg p-4 item-row">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-medium text-gray-800">Item #${index + 1}</h4>
                <button type="button" class="text-red-600 text-sm btn-remove-item">Hapus</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-700">Aset <span class="text-red-500">*</span></label>
                    <select name="items[${index}][data_aset_kolektif_id]" required class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        ${buildOptions(asetOptions, item.data_aset_kolektif_id)}
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-700">Jumlah <span class="text-red-500">*</span></label>
                    <input type="number" min="1" name="items[${index}][jumlah]" value="${item.jumlah ?? 1}" required class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="md:col-span-3">
                    <label class="text-sm text-gray-700">Catatan Item</label>
                    <textarea name="items[${index}][catatan_item]" rows="2" class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">${item.catatan_item ?? ''}</textarea>
                </div>
            </div>
        </div>`;
    }

    function bindRowEvents() {
        itemsContainer.querySelectorAll('.btn-remove-item').forEach((button) => {
            button.addEventListener('click', () => {
                if (itemsContainer.querySelectorAll('.item-row').length <= 1) {
                    return;
                }

                button.closest('.item-row').remove();
                reindexItems();
            });
        });
    }

    function reindexItems() {
        const rows = itemsContainer.querySelectorAll('.item-row');
        const state = [];

        rows.forEach((row) => {
            state.push({
                data_aset_kolektif_id: row.querySelector('[name*="[data_aset_kolektif_id]"]').value,
                jumlah: row.querySelector('[name*="[jumlah]"]').value,
                catatan_item: row.querySelector('[name*="[catatan_item]"]').value,
            });
        });

        itemsContainer.innerHTML = state.map((item, index) => renderItem(item, index)).join('');
        bindRowEvents();
    }

    addItemButton.addEventListener('click', () => {
        const index = itemsContainer.querySelectorAll('.item-row').length;
        itemsContainer.insertAdjacentHTML('beforeend', renderItem({
            data_aset_kolektif_id: '',
            jumlah: 1,
            catatan_item: '',
        }, index));
        bindRowEvents();
    });

    itemsContainer.innerHTML = initialItems.map((item, index) => renderItem(item, index)).join('');
    bindRowEvents();
</script>
