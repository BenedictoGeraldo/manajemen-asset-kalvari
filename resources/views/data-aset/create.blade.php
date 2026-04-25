@extends('layouts.main')

@section('title', 'Tambah Aset Baru')
@section('page-title', 'Tambah Aset Baru')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Tambah Aset Baru</h3>
            <p class="text-sm text-gray-600 mt-1">Isi formulir di bawah untuk menambah aset baru</p>
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
    <form action="{{ route('data-aset.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Informasi Dasar -->
        <div class="bg-white rounded-lg shadow p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Dasar</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="nama_aset" class="block text-sm font-medium text-gray-700">
                        Nama Aset <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_aset" id="nama_aset" required autofocus
                           value="{{ old('nama_aset') }}"
                           class="mt-1 block w-full rounded-lg {{ $errors->has('nama_aset') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('nama_aset')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori_id" id="kategori_id" required
                            class="mt-1 block w-full rounded-lg {{ $errors->has('kategori_id') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
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
                           value="{{ old('tahun_pengadaan', date('Y')) }}"
                           class="mt-1 block w-full rounded-lg {{ $errors->has('tahun_pengadaan') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('tahun_pengadaan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="deskripsi_aset" class="block text-sm font-medium text-gray-700">
                        Deskripsi Aset
                    </label>
                    <textarea name="deskripsi_aset" id="deskripsi_aset" rows="3"
                              class="mt-1 block w-full rounded-lg {{ $errors->has('deskripsi_aset') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('deskripsi_aset') }}</textarea>
                    @error('deskripsi_aset')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="gambar_aset" class="block text-sm font-medium text-gray-700">
                        Gambar Aset
                    </label>
                    <input type="file" name="gambar_aset" id="gambar_aset" accept="image/png,image/jpeg,image/jpg,image/webp"
                           class="mt-1 block w-full rounded-lg {{ $errors->has('gambar_aset') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Opsional. Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB.</p>
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
                            class="mt-1 block w-full rounded-lg {{ $errors->has('ukuran_label') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Skala</option>
                        <option value="S" {{ old('ukuran_label') == 'S' ? 'selected' : '' }}>S (< 30x30x30 cm)</option>
                        <option value="M" {{ old('ukuran_label') == 'M' ? 'selected' : '' }}>M (< 100x100x100 cm)</option>
                        <option value="L" {{ old('ukuran_label') == 'L' ? 'selected' : '' }}>L (< 200x200x200 cm)</option>
                        <option value="XL" {{ old('ukuran_label') == 'XL' ? 'selected' : '' }}>XL (< 300x300x300 cm)</option>
                        <option value="XXL" {{ old('ukuran_label') == 'XXL' ? 'selected' : '' }}>XXL (> 300x300x300 cm)</option>
                    </select>
                </div>

                <div>
                    <label for="ukuran" class="block text-sm font-medium text-gray-700">Detail Ukuran (Dimensi)</label>
                    <input type="text" name="ukuran" id="ukuran"
                           value="{{ old('ukuran') }}"
                           placeholder="Contoh: 120cm x 60cm x 80cm"
                           class="mt-1 block w-full rounded-lg {{ $errors->has('ukuran') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('ukuran')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi_ukuran_bentuk" class="block text-sm font-medium text-gray-700">
                        Deskripsi Ukuran/Bentuk
                    </label>
                    <input type="text" name="deskripsi_ukuran_bentuk" id="deskripsi_ukuran_bentuk"
                           value="{{ old('deskripsi_ukuran_bentuk') }}"
                           placeholder="Contoh: Persegi panjang, kayu jati"
                           class="mt-1 block w-full rounded-lg {{ $errors->has('deskripsi_ukuran_bentuk') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                            class="mt-1 block w-full rounded-lg {{ $errors->has('lokasi_id') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Lokasi</option>
                        @foreach($lokasis as $lok)
                            <option value="{{ $lok->id }}" {{ old('lokasi_id') == $lok->id ? 'selected' : '' }}>
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
                            class="mt-1 block w-full rounded-lg {{ $errors->has('kondisi_id') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Kondisi</option>
                        @foreach($kondisis as $kon)
                            <option value="{{ $kon->id }}" {{ old('kondisi_id') == $kon->id ? 'selected' : '' }}>
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
                            class="mt-1 block w-full rounded-lg {{ $errors->has('label_penggunaan') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Kategori Penggunaan</option>
                        <option value="A" {{ old('label_penggunaan') == 'A' ? 'selected' : '' }}>A - Lokasi tetap tidak berubah</option>
                        <option value="B" {{ old('label_penggunaan') == 'B' ? 'selected' : '' }}>B - Lokasi berubah saat digunakan, penyimpanan tetap</option>
                        <option value="C" {{ old('label_penggunaan') == 'C' ? 'selected' : '' }}>C - Frekuensi berpindah dan menetap tinggi</option>
                        <option value="D" {{ old('label_penggunaan') == 'D' ? 'selected' : '' }}>D - Melekat pada PIC/Seseorang</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="kegunaan" class="block text-sm font-medium text-gray-700">
                        Fungsi/Kegunaan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kegunaan" id="kegunaan" required
                           value="{{ old('kegunaan') }}"
                           placeholder="Contoh: Untuk ibadah minggu"
                           class="mt-1 block w-full rounded-lg {{ $errors->has('kegunaan') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('kegunaan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="keterangan_kegunaan" class="block text-sm font-medium text-gray-700">
                        Keterangan Kegunaan
                    </label>
                    <textarea name="keterangan_kegunaan" id="keterangan_kegunaan" rows="2"
                              class="mt-1 block w-full rounded-lg {{ $errors->has('keterangan_kegunaan') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('keterangan_kegunaan') }}</textarea>
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
                           value="{{ old('jumlah_barang', 1) }}"
                           class="mt-1 block w-full rounded-lg {{ $errors->has('jumlah_barang') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('jumlah_barang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tipe_grup" class="block text-sm font-medium text-gray-700">
                        Tipe Grup <span class="text-red-500">*</span>
                    </label>
                    <select name="tipe_grup_v2" id="tipe_grup_v2" required
                            class="mt-1 block w-full rounded-lg {{ $errors->has('tipe_grup_v2') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Tipe</option>
                        <option value="Singular" {{ old('tipe_grup_v2') == 'Singular' ? 'selected' : '' }}>Singular</option>
                        <option value="Collective" {{ old('tipe_grup_v2') == 'Collective' ? 'selected' : '' }}>Collective</option>
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
                           value="{{ old('keterangan_tipe_grup') }}"
                           placeholder="Contoh: 1 set terdiri dari 6 kursi"
                           class="mt-1 block w-full rounded-lg {{ $errors->has('keterangan_tipe_grup') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                            class="mt-1 block w-full rounded-lg {{ $errors->has('sumber_dana') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Sumber Dana</option>
                        <option value="Gereja" {{ old('sumber_dana') == 'Gereja' ? 'selected' : '' }}>Gereja</option>
                        <option value="Keuskupan / Titipan" {{ old('sumber_dana') == 'Keuskupan / Titipan' ? 'selected' : '' }}>Keuskupan / Titipan</option>
                        <option value="Pribadi / Group di hibahkan ke gereja" {{ old('sumber_dana') == 'Pribadi / Group di hibahkan ke gereja' ? 'selected' : '' }}>Pribadi / Group di hibahkan ke gereja</option>
                        <option value="Pribadi / Group" {{ old('sumber_dana') == 'Pribadi / Group' ? 'selected' : '' }}>Pribadi / Group</option>
                    </select>
                </div>

                <div>
                    <label for="nilai_budget" class="block text-sm font-medium text-gray-700">Nilai Anggaran (Rp)</label>
                    <input type="number" name="nilai_budget" id="nilai_budget" min="0" step="1000"
                           value="{{ old('nilai_budget') }}"
                           placeholder="Contoh: 5000000"
                           class="mt-1 block w-full rounded-lg {{ $errors->has('nilai_budget') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('nilai_budget')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="keterangan_budget" class="block text-sm font-medium text-gray-700">
                        Keterangan Anggaran
                    </label>
                    <input type="text" name="keterangan_budget" id="keterangan_budget"
                           value="{{ old('keterangan_budget') }}"
                           placeholder="Contoh: Dana APBG 2024"
                           class="mt-1 block w-full rounded-lg {{ $errors->has('keterangan_budget') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('keterangan_budget')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nilai_pengadaan_total" class="block text-sm font-medium text-gray-700">
                        Nilai Pengadaan Total (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="nilai_pengadaan_total" id="nilai_pengadaan_total" required min="0" step="1000"
                           value="{{ old('nilai_pengadaan_total') }}"
                           placeholder="Contoh: 4500000"
                           required
                           class="mt-1 block w-full rounded-lg {{ $errors->has('nilai_pengadaan_total') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('nilai_pengadaan_total')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nilai_pengadaan_per_unit" class="block text-sm font-medium text-gray-700">
                        Nilai Per Unit (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="nilai_pengadaan_per_unit" id="nilai_pengadaan_per_unit" required min="0" step="1000"
                           value="{{ old('nilai_pengadaan_per_unit') }}"
                           placeholder="Contoh: 750000"
                           class="mt-1 block w-full rounded-lg {{ $errors->has('nilai_pengadaan_per_unit') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Departemen</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('parent_department_id') == $dept->id ? 'selected' : '' }}>
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
                            class="mt-1 block w-full rounded-lg {{ $errors->has('department_id') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih Sub Departemen (Pilih Departemen terlebih dahulu)</option>
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
                        class="mt-1 block w-full rounded-lg {{ $errors->has('pengelola_id') ? 'border-red-500' : 'border-gray-300' }} shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Pilih Pengelola</option>
                    @foreach($pengelolas as $pg)
                        <option value="{{ $pg->id }}" {{ old('pengelola_id') == $pg->id ? 'selected' : '' }}>
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
                        deptSelect.innerHTML = '<option value="">Pilih Sub Departemen (Pilih Departemen terlebih dahulu)</option>';
                        return;
                    }

                    fetch(`{{ route('get-sub-departments') }}?parent_id=${parentId}`)
                        .then(response => response.json())
                        .then(data => {
                            deptSelect.innerHTML = '<option value="">Pilih Sub Departemen</option>';
                            if (data.length === 0) {
                                // If no sub-departments, use parent itself as an option or show message
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
            <button type="submit"
                    class="btn-a-sm">
                Simpan Aset
            </button>
        </div>
    </form>
</div>
@endsection

