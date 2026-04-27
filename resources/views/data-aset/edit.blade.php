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
           class="btn-c-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Batal
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('data-aset.update', $aset->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6"
          x-data="{
              formChanged: false,
              showModal: false,
              originalData: {},
              hasExistingImage: {{ $aset->gambar_aset_base64 ? 'true' : 'false' }},
              removeImage: {{ old('hapus_gambar_aset') ? 'true' : 'false' }},
              initialRemoveImage: {{ old('hapus_gambar_aset') ? 'true' : 'false' }},
              imagePreviewUrl: '',
              hasImageActionChange() {
                  return this.removeImage !== this.initialRemoveImage || this.imagePreviewUrl !== '';
              },
              toggleRemoveImage() {
                  const nextState = !this.removeImage;
                  this.removeImage = nextState;

                  if (!nextState) {
                      this.clearSelectedImage();
                  }

                  this.$nextTick(() => this.checkChanges());
                  this.formChanged = this.hasImageActionChange() || this.formChanged;
              },
              onImageSelected(event) {
                  const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;

                  if (this.imagePreviewUrl && this.imagePreviewUrl.startsWith('blob:')) {
                      URL.revokeObjectURL(this.imagePreviewUrl);
                  }

                  if (file) {
                      this.imagePreviewUrl = URL.createObjectURL(file);
                      if (this.hasExistingImage) {
                          this.removeImage = true;
                      }
                  } else {
                      this.imagePreviewUrl = '';
                  }

                  this.$nextTick(() => this.checkChanges());
                  this.formChanged = this.hasImageActionChange() || this.formChanged;
              },
              clearSelectedImage() {
                  const fileInput = document.getElementById('gambar_aset');
                  if (fileInput) {
                      fileInput.value = '';
                  }

                  if (this.imagePreviewUrl && this.imagePreviewUrl.startsWith('blob:')) {
                      URL.revokeObjectURL(this.imagePreviewUrl);
                  }

                  this.imagePreviewUrl = '';
              },
              getComparableData(form) {
                  const formData = new FormData(form);
                  const comparable = {};

                  for (const [key, value] of formData.entries()) {
                      if (value instanceof File) {
                          comparable[key] = value.name || '';
                      } else {
                          comparable[key] = value;
                      }
                  }

                  return comparable;
              },
              init() {
                  // Store original form data
                  const form = this.$el;
                  this.originalData = this.getComparableData(form);
                  this.initialRemoveImage = this.removeImage;
              },
              checkChanges() {
                  const form = this.$el;
                  const current = this.getComparableData(form);

                  // Compare current with original
                  const baseChanged = JSON.stringify(current) !== JSON.stringify(this.originalData);
                  const imageActionChanged = this.hasImageActionChange();

                  this.formChanged = baseChanged || imageActionChanged;
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
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('nama_aset') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('nama_aset')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori_id" id="kategori_id" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('kategori_id') ? 'border-red-500' : 'border-gray-300' }}">
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
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('tahun_pengadaan') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('tahun_pengadaan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="deskripsi_aset" class="block text-sm font-medium text-gray-700">
                        Deskripsi Aset
                    </label>
                    <textarea name="deskripsi_aset" id="deskripsi_aset" rows="3"
                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('deskripsi_aset') ? 'border-red-500' : 'border-gray-300' }}">{{ old('deskripsi_aset', $aset->deskripsi_aset) }}</textarea>
                    @error('deskripsi_aset')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="gambar_aset" class="block text-sm font-medium text-gray-700">
                        Gambar Aset
                    </label>
                    <input type="hidden" name="hapus_gambar_aset" :value="removeImage ? 1 : 0">
                    @if($aset->gambar_aset_base64)
                        <div x-show="hasExistingImage && !removeImage && !imagePreviewUrl" class="mt-2 mb-3 rounded-lg overflow-hidden border border-gray-200 bg-gray-50 p-2">
                            <img src="{{ $aset->gambar_aset_base64 }}" alt="Gambar {{ $aset->nama_aset }}" class="max-h-64 w-auto object-contain mx-auto rounded">
                        </div>

                        <div class="mb-3">
                            <button type="button"
                                    @click="toggleRemoveImage()"
                                    :class="removeImage ? 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200' : 'bg-red-50 text-red-700 border-red-200 hover:bg-red-100'"
                                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg border transition-colors duration-150">
                                <svg x-show="!removeImage" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <svg x-show="removeImage" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span x-show="!removeImage">Hapus Gambar</span>
                                <span x-show="removeImage">Batal Hapus</span>
                            </button>
                        </div>
                    @endif

                    <div x-show="imagePreviewUrl" class="mt-2 mb-3 rounded-lg overflow-hidden border border-blue-200 bg-blue-50 p-2">
                        <img :src="imagePreviewUrl" alt="Preview gambar baru" class="max-h-64 w-auto object-contain mx-auto rounded">
                        <p class="mt-2 text-xs text-blue-700 text-center">Preview gambar baru</p>
                    </div>

                    <input type="file" name="gambar_aset" id="gambar_aset" accept="image/png,image/jpeg,image/jpg,image/webp"
                           @change="onImageSelected($event)"
                           :disabled="hasExistingImage && !removeImage"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('gambar_aset') ? 'border-red-500' : 'border-gray-300' }}">
                    <p class="mt-1 text-xs text-gray-500">Opsional. Maksimal 2MB. Jika gambar lama belum ditandai hapus, upload dinonaktifkan.</p>
                    @if($aset->gambar_aset_base64)
                        <p class="mt-1 text-xs text-amber-600" x-show="!removeImage">Upload dinonaktifkan karena aset sudah memiliki gambar.</p>
                        <p class="mt-1 text-xs text-green-600" x-show="removeImage">Gambar lama ditandai dihapus. Anda bisa langsung pilih gambar baru lalu klik Update Aset.</p>
                    @endif
                    @error('hapus_gambar_aset')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('gambar_aset')
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
                    <label for="ukuran_label" class="block text-sm font-medium text-gray-700">Skala Ukuran</label>
                    <select name="ukuran_label" id="ukuran_label"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('ukuran_label') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Pilih Skala</option>
                        <option value="S" {{ old('ukuran_label', $aset->ukuran_label) == 'S' ? 'selected' : '' }}>S (< 30x30x30 cm)</option>
                        <option value="M" {{ old('ukuran_label', $aset->ukuran_label) == 'M' ? 'selected' : '' }}>M (< 100x100x100 cm)</option>
                        <option value="L" {{ old('ukuran_label', $aset->ukuran_label) == 'L' ? 'selected' : '' }}>L (< 200x200x200 cm)</option>
                        <option value="XL" {{ old('ukuran_label', $aset->ukuran_label) == 'XL' ? 'selected' : '' }}>XL (< 300x300x300 cm)</option>
                        <option value="XXL" {{ old('ukuran_label', $aset->ukuran_label) == 'XXL' ? 'selected' : '' }}>XXL (> 300x300x300 cm)</option>
                    </select>
                </div>

                <div>
                    <label for="ukuran" class="block text-sm font-medium text-gray-700">Detail Ukuran (Dimensi)</label>
                    <input type="text" name="ukuran" id="ukuran"
                           value="{{ old('ukuran', $aset->ukuran) }}"
                           placeholder="Contoh: 120cm x 60cm x 80cm"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('ukuran') ? 'border-red-500' : 'border-gray-300' }}">
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
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('deskripsi_ukuran_bentuk') ? 'border-red-500' : 'border-gray-300' }}">
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
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('lokasi_id') ? 'border-red-500' : 'border-gray-300' }}">
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
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('kondisi_id') ? 'border-red-500' : 'border-gray-300' }}">
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
                    <label for="label_penggunaan" class="block text-sm font-medium text-gray-700">
                        Kategori Penggunaan <span class="text-red-500">*</span>
                    </label>
                    <select name="label_penggunaan" id="label_penggunaan" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('label_penggunaan') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Pilih Kategori Penggunaan</option>
                        <option value="A" {{ old('label_penggunaan', $aset->label_penggunaan) == 'A' ? 'selected' : '' }}>A - Lokasi tetap tidak berubah</option>
                        <option value="B" {{ old('label_penggunaan', $aset->label_penggunaan) == 'B' ? 'selected' : '' }}>B - Lokasi berubah saat digunakan, penyimpanan tetap</option>
                        <option value="C" {{ old('label_penggunaan', $aset->label_penggunaan) == 'C' ? 'selected' : '' }}>C - Frekuensi berpindah dan menetap tinggi</option>
                        <option value="D" {{ old('label_penggunaan', $aset->label_penggunaan) == 'D' ? 'selected' : '' }}>D - Melekat pada PIC/Seseorang</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="kegunaan" class="block text-sm font-medium text-gray-700">
                        Fungsi/Kegunaan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kegunaan" id="kegunaan" required
                           value="{{ old('kegunaan', $aset->kegunaan) }}"
                           placeholder="Contoh: Untuk ibadah minggu"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('kegunaan') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('kegunaan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="keterangan_kegunaan" class="block text-sm font-medium text-gray-700">
                        Keterangan Kegunaan
                    </label>
                    <textarea name="keterangan_kegunaan" id="keterangan_kegunaan" rows="2"
                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('keterangan_kegunaan') ? 'border-red-500' : 'border-gray-300' }}">{{ old('keterangan_kegunaan', $aset->keterangan_kegunaan) }}</textarea>
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
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('jumlah_barang') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('jumlah_barang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tipe_grup" class="block text-sm font-medium text-gray-700">
                        Tipe Grup <span class="text-red-500">*</span>
                    </label>
                    <select name="tipe_grup_v2" id="tipe_grup_v2" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('tipe_grup_v2') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Pilih Tipe</option>
                        <option value="Singular" {{ old('tipe_grup_v2', $aset->tipe_grup_v2) == 'Singular' ? 'selected' : '' }}>Singular</option>
                        <option value="Collective" {{ old('tipe_grup_v2', $aset->tipe_grup_v2) == 'Collective' ? 'selected' : '' }}>Collective</option>
                    </select>
                    <input type="hidden" name="tipe_grup" value="individual">
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
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('keterangan_tipe_grup') ? 'border-red-500' : 'border-gray-300' }}">
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
                    <label for="sumber_dana" class="block text-sm font-medium text-gray-700">
                        Sumber Dana <span class="text-red-500">*</span>
                    </label>
                    <select name="sumber_dana" id="sumber_dana" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('sumber_dana') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Pilih Sumber Dana</option>
                        <option value="Gereja" {{ old('sumber_dana', $aset->sumber_dana) == 'Gereja' ? 'selected' : '' }}>Gereja</option>
                        <option value="Keuskupan / Titipan" {{ old('sumber_dana', $aset->sumber_dana) == 'Keuskupan / Titipan' ? 'selected' : '' }}>Keuskupan / Titipan</option>
                        <option value="Pribadi / Group di hibahkan ke gereja" {{ old('sumber_dana', $aset->sumber_dana) == 'Pribadi / Group di hibahkan ke gereja' ? 'selected' : '' }}>Pribadi / Group di hibahkan ke gereja</option>
                        <option value="Pribadi / Group" {{ old('sumber_dana', $aset->sumber_dana) == 'Pribadi / Group' ? 'selected' : '' }}>Pribadi / Group</option>
                    </select>
                </div>

                <div>
                    <label for="nilai_budget" class="block text-sm font-medium text-gray-700">Nilai Anggaran (Rp)</label>
                    <input type="number" name="nilai_budget" id="nilai_budget" min="0" step="1000"
                           value="{{ old('nilai_budget', $aset->nilai_budget) }}"
                           placeholder="Contoh: 5000000"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('nilai_budget') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('nilai_budget')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="keterangan_budget" class="block text-sm font-medium text-gray-700">
                        Keterangan Anggaran
                    </label>
                    <input type="text" name="keterangan_budget" id="keterangan_budget"
                           value="{{ old('keterangan_budget', $aset->keterangan_budget) }}"
                           placeholder="Contoh: Dana APBG 2024"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('keterangan_budget') ? 'border-red-500' : 'border-gray-300' }}">
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
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('nilai_pengadaan_total') ? 'border-red-500' : 'border-gray-300' }}">
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
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('nilai_pengadaan_per_unit') ? 'border-red-500' : 'border-gray-300' }}">
                    @error('nilai_pengadaan_per_unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Departemen & Sub Departemen -->
        <div class="bg-white rounded-lg shadow p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Departemen</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="parent_department_id" class="block text-sm font-medium text-gray-700">
                        Departemen <span class="text-red-500">*</span>
                    </label>
                    <select id="parent_department_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih Departemen</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ (old('parent_department_id', $currentDepartmentId) == $dept->id) ? 'selected' : '' }}>
                                {{ $dept->code }} - {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700">
                        Sub Departemen <span class="text-red-500">*</span>
                    </label>
                    <select name="department_id" id="department_id" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('department_id') ? 'border-red-500' : 'border-gray-300' }}">
                        <option value="">Pilih Sub Departemen</option>
                        @foreach($subDepartments as $sub)
                            <option value="{{ $sub->id }}" {{ (old('department_id', $aset->department_id) == $sub->id) ? 'selected' : '' }}>
                                {{ $sub->code }} - {{ $sub->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
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
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('pengelola_id') ? 'border-red-500' : 'border-gray-300' }}">
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

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const parentDeptSelect = document.getElementById('parent_department_id');
                const deptSelect = document.getElementById('department_id');

                parentDeptSelect.addEventListener('change', function() {
                    const parentId = this.value;
                    deptSelect.innerHTML = '<option value="">Loading...</option>';

                    if (!parentId) {
                        deptSelect.innerHTML = '<option value="">Pilih Sub Departemen</option>';
                        return;
                    }

                    fetch(`{{ route('get-sub-departments') }}?parent_id=${parentId}`)
                        .then(response => response.json())
                        .then(data => {
                            deptSelect.innerHTML = '<option value="">Pilih Sub Departemen</option>';
                            if (data.length === 0) {
                                const option = document.createElement('option');
                                option.value = parentId;
                                option.textContent = parentDeptSelect.options[parentDeptSelect.selectedIndex].text + ' (Utama)';
                                deptSelect.appendChild(option);
                            } else {
                                data.forEach(sub => {
                                    const option = document.createElement('option');
                                    option.value = sub.id;
                                    option.textContent = `${sub.code} - ${sub.name}`;
                                    deptSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            deptSelect.innerHTML = '<option value="">Error loading sub departments</option>';
                        });
                });
            });
        </script>
        @endpush

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('data-aset.index') }}" data-navigate
               class="btn-c-sm">
                Batal
            </a>
            <button type="button"
                    @click="(formChanged || hasImageActionChange()) ? showModal = true : null"
                    :disabled="!(formChanged || hasImageActionChange())"
                    :class="(formChanged || hasImageActionChange()) ? 'bg-blue-600 hover:bg-blue-700 cursor-pointer' : 'bg-gray-400 cursor-not-allowed'"
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
                                class="btn-a-sm w-full inline-flex justify-center sm:ml-3 sm:w-auto sm:text-sm">
                            Update
                        </button>
                        <button type="button"
                                @click="showModal = false"
                                class="btn-c-outline mt-3 w-full inline-flex justify-center sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

