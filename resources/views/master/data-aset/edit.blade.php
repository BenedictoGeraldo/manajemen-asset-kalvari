@extends('layouts.main')

@section('title', 'Edit Aset - ' . $aset->nama_aset)
@section('page-title', 'Edit Aset')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Edit Aset</h3>
            <p class="text-sm text-gray-600 mt-1">{{ $aset->nama_aset }} ({{ $aset->kode_aset }})</p>
        </div>
        <a href="{{ route('data-aset.index') }}" data-navigate
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Batal
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('data-aset.update', $aset->id) }}" method="POST" class="space-y-6"
          x-data="{
              formChanged: false,
              showModal: false,
              originalData: {},
              init() {
                  // Store original form data
                  const form = this.$el;
                  const formData = new FormData(form);
                  this.originalData = Object.fromEntries(formData);
              },
              checkChanges() {
                  const form = this.$el;
                  const currentData = new FormData(form);
                  const current = Object.fromEntries(currentData);

                  // Compare current with original
                  this.formChanged = JSON.stringify(current) !== JSON.stringify(this.originalData);
              }
          }"
          @input="checkChanges()"
          @change="checkChanges()">
        @csrf
        @method('PUT')

        <!-- Informasi Dasar -->
        <div class="bg-white rounded-lg shadow p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Dasar</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="nama_aset" class="block text-sm font-medium text-gray-700">
                        Nama Aset <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_aset" id="nama_aset" required
                           value="{{ old('nama_aset', $aset->nama_aset) }}"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nama_aset') border-red-500 @enderror">
                    @error('nama_aset')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori_id" id="kategori_id" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('kategori_id') border-red-500 @enderror">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id', $aset->kategori_id) == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tahun_pengadaan" class="block text-sm font-medium text-gray-700">
                        Tahun Pengadaan <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="tahun_pengadaan" id="tahun_pengadaan" required min="1900" max="{{ date('Y') + 1 }}"
                           value="{{ old('tahun_pengadaan', $aset->tahun_pengadaan) }}"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tahun_pengadaan') border-red-500 @enderror">
                    @error('tahun_pengadaan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="deskripsi_aset" class="block text-sm font-medium text-gray-700">
                        Deskripsi Aset
                    </label>
                    <textarea name="deskripsi_aset" id="deskripsi_aset" rows="3"
                              class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('deskripsi_aset') border-red-500 @enderror">{{ old('deskripsi_aset', $aset->deskripsi_aset) }}</textarea>
                    @error('deskripsi_aset')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Ukuran & Bentuk -->
        <div class="bg-white rounded-lg shadow p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Ukuran & Bentuk</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="ukuran" class="block text-sm font-medium text-gray-700">Ukuran</label>
                    <input type="text" name="ukuran" id="ukuran"
                           value="{{ old('ukuran', $aset->ukuran) }}"
                           placeholder="Contoh: 120cm x 60cm x 80cm"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('ukuran') border-red-500 @enderror">
                    @error('ukuran')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi_ukuran_bentuk" class="block text-sm font-medium text-gray-700">
                        Deskripsi Ukuran/Bentuk
                    </label>
                    <input type="text" name="deskripsi_ukuran_bentuk" id="deskripsi_ukuran_bentuk"
                           value="{{ old('deskripsi_ukuran_bentuk', $aset->deskripsi_ukuran_bentuk) }}"
                           placeholder="Contoh: Persegi panjang, kayu jati"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('deskripsi_ukuran_bentuk') border-red-500 @enderror">
                    @error('deskripsi_ukuran_bentuk')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Lokasi & Kegunaan -->
        <div class="bg-white rounded-lg shadow p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Lokasi & Kegunaan</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="lokasi_id" class="block text-sm font-medium text-gray-700">
                        Lokasi <span class="text-red-500">*</span>
                    </label>
                    <select name="lokasi_id" id="lokasi_id" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('lokasi_id') border-red-500 @enderror">
                        <option value="">Pilih Lokasi</option>
                        @foreach($lokasis as $lok)
                            <option value="{{ $lok->id }}" {{ old('lokasi_id', $aset->lokasi_id) == $lok->id ? 'selected' : '' }}>
                                {{ $lok->nama_lokasi }} - {{ $lok->lokasi_lengkap }}
                            </option>
                        @endforeach
                    </select>
                    @error('lokasi_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kondisi_id" class="block text-sm font-medium text-gray-700">
                        Kondisi <span class="text-red-500">*</span>
                    </label>
                    <select name="kondisi_id" id="kondisi_id" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('kondisi_id') border-red-500 @enderror">
                        <option value="">Pilih Kondisi</option>
                        @foreach($kondisis as $kon)
                            <option value="{{ $kon->id }}" {{ old('kondisi_id', $aset->kondisi_id) == $kon->id ? 'selected' : '' }}>
                                {{ $kon->nama_kondisi }} - {{ $kon->keterangan }}
                            </option>
                        @endforeach
                    </select>
                    @error('kondisi_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="kegunaan" class="block text-sm font-medium text-gray-700">
                        Kegunaan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kegunaan" id="kegunaan" required
                           value="{{ old('kegunaan', $aset->kegunaan) }}"
                           placeholder="Contoh: Untuk ibadah minggu"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('kegunaan') border-red-500 @enderror">
                    @error('kegunaan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="keterangan_kegunaan" class="block text-sm font-medium text-gray-700">
                        Keterangan Kegunaan
                    </label>
                    <textarea name="keterangan_kegunaan" id="keterangan_kegunaan" rows="2"
                              class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('keterangan_kegunaan') border-red-500 @enderror">{{ old('keterangan_kegunaan', $aset->keterangan_kegunaan) }}</textarea>
                    @error('keterangan_kegunaan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Jumlah & Tipe -->
        <div class="bg-white rounded-lg shadow p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Jumlah & Tipe</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="jumlah_barang" class="block text-sm font-medium text-gray-700">
                        Jumlah Barang <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="jumlah_barang" id="jumlah_barang" required min="1"
                           value="{{ old('jumlah_barang', $aset->jumlah_barang) }}"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('jumlah_barang') border-red-500 @enderror">
                    @error('jumlah_barang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tipe_grup" class="block text-sm font-medium text-gray-700">
                        Tipe Grup <span class="text-red-500">*</span>
                    </label>
                    <select name="tipe_grup" id="tipe_grup" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tipe_grup') border-red-500 @enderror">
                        <option value="">Pilih Tipe</option>
                        <option value="individual" {{ old('tipe_grup', $aset->tipe_grup) == 'individual' ? 'selected' : '' }}>Individual</option>
                        <option value="set" {{ old('tipe_grup', $aset->tipe_grup) == 'set' ? 'selected' : '' }}>Set</option>
                        <option value="grup" {{ old('tipe_grup', $aset->tipe_grup) == 'grup' ? 'selected' : '' }}>Grup</option>
                    </select>
                    @error('tipe_grup')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="keterangan_tipe_grup" class="block text-sm font-medium text-gray-700">
                        Keterangan Tipe Grup
                    </label>
                    <input type="text" name="keterangan_tipe_grup" id="keterangan_tipe_grup"
                           value="{{ old('keterangan_tipe_grup', $aset->keterangan_tipe_grup) }}"
                           placeholder="Contoh: 1 set terdiri dari 6 kursi"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('keterangan_tipe_grup') border-red-500 @enderror">
                    @error('keterangan_tipe_grup')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Anggaran & Nilai -->
        <div class="bg-white rounded-lg shadow p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Anggaran & Nilai Pengadaan</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="budget" class="block text-sm font-medium text-gray-700">Budget (Rp)</label>
                    <input type="number" name="budget" id="budget" min="0" step="1000"
                           value="{{ old('budget', $aset->budget) }}"
                           placeholder="Contoh: 5000000"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('budget') border-red-500 @enderror">
                    @error('budget')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="keterangan_budget" class="block text-sm font-medium text-gray-700">
                        Keterangan Budget
                    </label>
                    <input type="text" name="keterangan_budget" id="keterangan_budget"
                           value="{{ old('keterangan_budget', $aset->keterangan_budget) }}"
                           placeholder="Contoh: Dana APBG 2024"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('keterangan_budget') border-red-500 @enderror">
                    @error('keterangan_budget')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nilai_pengadaan_total" class="block text-sm font-medium text-gray-700">
                        Nilai Pengadaan Total (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="nilai_pengadaan_total" id="nilai_pengadaan_total" required min="0" step="1000"
                           value="{{ old('nilai_pengadaan_total', $aset->nilai_pengadaan_total) }}"
                           placeholder="Contoh: 4500000"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nilai_pengadaan_total') border-red-500 @enderror">
                    @error('nilai_pengadaan_total')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nilai_pengadaan_per_unit" class="block text-sm font-medium text-gray-700">
                        Nilai Per Unit (Rp)
                    </label>
                    <input type="number" name="nilai_pengadaan_per_unit" id="nilai_pengadaan_per_unit" min="0" step="1000"
                           value="{{ old('nilai_pengadaan_per_unit', $aset->nilai_pengadaan_per_unit) }}"
                           placeholder="Contoh: 750000"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nilai_pengadaan_per_unit') border-red-500 @enderror">
                    @error('nilai_pengadaan_per_unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Pengelola -->
        <div class="bg-white rounded-lg shadow p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Pengelola</h4>
            <div>
                <label for="pengelola_id" class="block text-sm font-medium text-gray-700">
                    Pengelola Aset <span class="text-red-500">*</span>
                </label>
                <select name="pengelola_id" id="pengelola_id" required
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('pengelola_id') border-red-500 @enderror">
                    <option value="">Pilih Pengelola</option>
                    @foreach($pengelolas as $pg)
                        <option value="{{ $pg->id }}" {{ old('pengelola_id', $aset->pengelola_id) == $pg->id ? 'selected' : '' }}>
                            {{ $pg->nama_pengelola }} - {{ $pg->jabatan }} ({{ $pg->departemen }})
                        </option>
                    @endforeach
                </select>
                @error('pengelola_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('data-aset.index') }}" data-navigate
               class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-150">
                Batal
            </a>
            <button type="button"
                    @click="formChanged ? showModal = true : null"
                    :disabled="!formChanged"
                    :class="formChanged ? 'bg-blue-600 hover:bg-blue-700 cursor-pointer' : 'bg-gray-400 cursor-not-allowed'"
                    class="px-6 py-2 text-white font-medium rounded-lg transition-colors duration-150">
                Update Aset
            </button>
        </div>

        <!-- Update Confirmation Modal -->
        <div x-show="showModal"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                     @click="showModal = false"
                     aria-hidden="true"></div>

                <!-- Center modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Konfirmasi Update Aset
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Apakah Anda yakin ingin menyimpan perubahan data aset <strong>{{ $aset->nama_aset }}</strong>?
                                        Perubahan akan langsung tersimpan di sistem.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-150">
                            Update
                        </button>
                        <button type="button"
                                @click="showModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-150">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
