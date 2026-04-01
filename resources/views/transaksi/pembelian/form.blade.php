@php
    $submitLabel = $submitLabel ?? 'Simpan';

    $existingItems = isset($pembelian)
        ? $pembelian->items->map(function ($item) {
            return [
                'nama_item' => $item->nama_item,
                'kategori_id' => $item->kategori_id,
                'deskripsi' => $item->deskripsi,
                'kegunaan' => $item->kegunaan,
                'jumlah' => $item->jumlah,
                'harga_satuan' => $item->harga_satuan,
                'lokasi_id' => $item->lokasi_id,
                'kondisi_id' => $item->kondisi_id,
                'pengelola_id' => $item->pengelola_id,
                'tahun_pengadaan' => $item->tahun_pengadaan,
                'catatan' => $item->catatan,
            ];
        })->toArray()
        : [[
            'nama_item' => '',
            'kategori_id' => '',
            'deskripsi' => '',
            'kegunaan' => '',
            'jumlah' => 1,
            'harga_satuan' => 0,
            'lokasi_id' => '',
            'kondisi_id' => '',
            'pengelola_id' => '',
            'tahun_pengadaan' => date('Y'),
            'catatan' => '',
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
        <label for="tanggal_pembelian" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembelian <span class="text-red-500">*</span></label>
        <input type="date" id="tanggal_pembelian" name="tanggal_pembelian" required
               value="{{ old('tanggal_pembelian', isset($pembelian) ? optional($pembelian->tanggal_pembelian)->format('Y-m-d') : date('Y-m-d')) }}"
             class="w-full rounded-lg @error('tanggal_pembelian') border-red-500 @else border-gray-300 @enderror focus:border-blue-500 focus:ring-blue-500">
        @error('tanggal_pembelian')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
        <select id="status" name="status" class="w-full rounded-lg @error('status') border-red-500 @else border-gray-300 @enderror focus:border-blue-500 focus:ring-blue-500">
            @php $selectedStatus = old('status', $pembelian->status ?? 'draft'); @endphp
            <option value="draft" {{ $selectedStatus === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="diajukan" {{ $selectedStatus === 'diajukan' ? 'selected' : '' }}>Diajukan</option>
            <option value="dibatalkan" {{ $selectedStatus === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="vendor_nama" class="block text-sm font-medium text-gray-700 mb-1">Vendor <span class="text-red-500">*</span></label>
        <input type="text" id="vendor_nama" name="vendor_nama" required value="{{ old('vendor_nama', $pembelian->vendor_nama ?? '') }}"
             class="w-full rounded-lg @error('vendor_nama') border-red-500 @else border-gray-300 @enderror focus:border-blue-500 focus:ring-blue-500">
        @error('vendor_nama')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="vendor_kontak" class="block text-sm font-medium text-gray-700 mb-1">Kontak Vendor</label>
        <input type="text" id="vendor_kontak" name="vendor_kontak" value="{{ old('vendor_kontak', $pembelian->vendor_kontak ?? '') }}"
               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
    </div>

    <div>
        <label for="sumber_dana" class="block text-sm font-medium text-gray-700 mb-1">Sumber Dana</label>
        <input type="text" id="sumber_dana" name="sumber_dana" value="{{ old('sumber_dana', $pembelian->sumber_dana ?? '') }}"
               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
    </div>

    <div class="md:col-span-2">
        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
        <textarea id="catatan" name="catatan" rows="2"
                  class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('catatan', $pembelian->catatan ?? '') }}</textarea>
    </div>
</div>

@error('items.*')
    <p class="text-sm text-red-600">{{ $message }}</p>
@enderror
</div>

<div class="mt-8 border-t pt-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Item Pembelian</h3>
        <button type="button" id="btn-add-item" class="btn-a-sm">Tambah Item</button>
    </div>

    @error('items')<p class="mb-3 text-sm text-red-600">{{ $message }}</p>@enderror

    <div id="items-container" class="space-y-4"></div>

    <div class="mt-4 text-right">
        <p class="text-sm text-gray-600">Total Nilai Pembelian</p>
        <p id="grand-total" class="text-xl font-bold text-gray-900">Rp 0</p>
    </div>
</div>

<div class="mt-6 flex items-center space-x-3">
    <button type="submit"
            class="btn-a-sm">
        {{ $submitLabel }}
    </button>
    <a href="{{ route('transaksi.pembelian.index') }}" data-navigate
       class="btn-c-sm">
        Batal
    </a>
</div>

<script>
    const itemsContainer = document.getElementById('items-container');
    const addItemButton = document.getElementById('btn-add-item');
    const grandTotalElement = document.getElementById('grand-total');
    const initialItems = @json($itemsState);

    function buildOptions(list, selected) {
        return list.map(option => {
            const isSelected = String(option.id) === String(selected) ? 'selected' : '';
            return `<option value="${option.id}" ${isSelected}>${option.label}</option>`;
        }).join('');
    }

    const kategoriOptions = [{ id: '', label: 'Pilih Kategori' }, ...@json($kategoris->map(fn($x) => ['id' => $x->id, 'label' => $x->nama_kategori])->values())];
    const lokasiOptions = [{ id: '', label: 'Pilih Lokasi' }, ...@json($lokasis->map(fn($x) => ['id' => $x->id, 'label' => $x->nama_lokasi])->values())];
    const kondisiOptions = [{ id: '', label: 'Pilih Kondisi' }, ...@json($kondisis->map(fn($x) => ['id' => $x->id, 'label' => $x->nama_kondisi])->values())];
    const pengelolaOptions = [{ id: '', label: 'Pilih Pengelola' }, ...@json($pengelolas->map(fn($x) => ['id' => $x->id, 'label' => $x->nama_pengelola])->values())];

    function renderItem(item, index) {
        const qty = item.jumlah || 1;
        const price = item.harga_satuan || 0;
        const subtotal = qty * price;

        return `
        <div class="border border-gray-200 rounded-lg p-4 item-row">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-medium text-gray-800">Item #${index + 1}</h4>
                <button type="button" class="action-delete-inline btn-remove-item">Hapus</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="lg:col-span-2">
                    <label class="text-sm text-gray-700">Nama Item *</label>
                    <input type="text" name="items[${index}][nama_item]" value="${item.nama_item ?? ''}" required class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-sm text-gray-700">Kategori *</label>
                    <select name="items[${index}][kategori_id]" required class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        ${buildOptions(kategoriOptions, item.kategori_id)}
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-700">Tahun Pengadaan *</label>
                    <input type="number" name="items[${index}][tahun_pengadaan]" value="${item.tahun_pengadaan ?? new Date().getFullYear()}" min="1900" max="${new Date().getFullYear() + 1}" required class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="lg:col-span-2">
                    <label class="text-sm text-gray-700">Kegunaan *</label>
                    <input type="text" name="items[${index}][kegunaan]" value="${item.kegunaan ?? ''}" required class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-sm text-gray-700">Jumlah *</label>
                    <input type="number" min="1" name="items[${index}][jumlah]" value="${qty}" required class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 input-qty">
                </div>

                <div>
                    <label class="text-sm text-gray-700">Harga Satuan *</label>
                    <input type="number" min="0" step="1000" name="items[${index}][harga_satuan]" value="${price}" required class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 input-price">
                </div>

                <div>
                    <label class="text-sm text-gray-700">Lokasi *</label>
                    <select name="items[${index}][lokasi_id]" required class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        ${buildOptions(lokasiOptions, item.lokasi_id)}
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-700">Kondisi *</label>
                    <select name="items[${index}][kondisi_id]" required class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        ${buildOptions(kondisiOptions, item.kondisi_id)}
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-700">Pengelola *</label>
                    <select name="items[${index}][pengelola_id]" required class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        ${buildOptions(pengelolaOptions, item.pengelola_id)}
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-700">Subtotal</label>
                    <input type="text" value="Rp ${Number(subtotal).toLocaleString('id-ID')}" readonly class="mt-1 w-full rounded-lg border-gray-300 bg-gray-50 input-subtotal">
                </div>

                <div class="lg:col-span-4">
                    <label class="text-sm text-gray-700">Deskripsi</label>
                    <textarea name="items[${index}][deskripsi]" rows="2" class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">${item.deskripsi ?? ''}</textarea>
                </div>

                <div class="lg:col-span-4">
                    <label class="text-sm text-gray-700">Catatan Item</label>
                    <textarea name="items[${index}][catatan]" rows="2" class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">${item.catatan ?? ''}</textarea>
                </div>
            </div>
        </div>`;
    }

    function reindexItems() {
        const rows = itemsContainer.querySelectorAll('.item-row');
        const state = [];

        rows.forEach((row) => {
            state.push({
                nama_item: row.querySelector('[name*="[nama_item]"]').value,
                kategori_id: row.querySelector('[name*="[kategori_id]"]').value,
                deskripsi: row.querySelector('[name*="[deskripsi]"]').value,
                kegunaan: row.querySelector('[name*="[kegunaan]"]').value,
                jumlah: row.querySelector('[name*="[jumlah]"]').value,
                harga_satuan: row.querySelector('[name*="[harga_satuan]"]').value,
                lokasi_id: row.querySelector('[name*="[lokasi_id]"]').value,
                kondisi_id: row.querySelector('[name*="[kondisi_id]"]').value,
                pengelola_id: row.querySelector('[name*="[pengelola_id]"]').value,
                tahun_pengadaan: row.querySelector('[name*="[tahun_pengadaan]"]').value,
                catatan: row.querySelector('[name*="[catatan]"]').value,
            });
        });

        itemsContainer.innerHTML = state.map((item, index) => renderItem(item, index)).join('');
        bindRowEvents();
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;

        itemsContainer.querySelectorAll('.item-row').forEach((row) => {
            const qty = Number(row.querySelector('.input-qty').value || 0);
            const price = Number(row.querySelector('.input-price').value || 0);
            const subtotal = qty * price;
            row.querySelector('.input-subtotal').value = 'Rp ' + subtotal.toLocaleString('id-ID');
            grandTotal += subtotal;
        });

        grandTotalElement.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
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

        itemsContainer.querySelectorAll('.input-qty, .input-price').forEach((input) => {
            input.addEventListener('input', calculateGrandTotal);
        });
    }

    addItemButton.addEventListener('click', () => {
        const rows = itemsContainer.querySelectorAll('.item-row');
        const index = rows.length;
        itemsContainer.insertAdjacentHTML('beforeend', renderItem({
            nama_item: '',
            kategori_id: '',
            deskripsi: '',
            kegunaan: '',
            jumlah: 1,
            harga_satuan: 0,
            lokasi_id: '',
            kondisi_id: '',
            pengelola_id: '',
            tahun_pengadaan: new Date().getFullYear(),
            catatan: '',
        }, index));

        bindRowEvents();
        calculateGrandTotal();
    });

    itemsContainer.innerHTML = initialItems.map((item, index) => renderItem(item, index)).join('');
    bindRowEvents();
    calculateGrandTotal();
</script>

