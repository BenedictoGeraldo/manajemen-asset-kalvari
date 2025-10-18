@extends('layouts.main')

@section('title', 'Manajemen Data Aset')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 md:mb-0">Daftar Aset Gereja</h2>
            <button id="btn-tambah-aset" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg transition duration-300 flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Aset Baru
            </button>
        </div>

        @if (session('success'))
            <div id="success-alert" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
                <p class="font-bold">Terjadi Kesalahan:</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Aset</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai (Rp)</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($asets as $aset)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $aset->kode_aset }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $aset->nama_aset }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $aset->kategori }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($aset->kondisi == 'Baik')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Baik</span>
                                @elseif ($aset->kondisi == 'Rusak Ringan')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Rusak Ringan</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rusak Berat</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format($aset->nilai_perolehan, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <button class="btn-edit-aset text-indigo-600 hover:text-indigo-900 mr-4"
                                        data-id="{{ $aset->id }}"
                                        data-asset='@json($aset)'>
                                    Edit
                                </button>
                                <form action="{{ route('data-aset.destroy', $aset) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset \'{{ $aset->nama_aset }}\'?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Belum ada data aset. Silakan tambahkan aset baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Link Paginasi --}}
        <div class="mt-6">
            {{ $asets->links() }}
        </div>
    </div>
</div>

<div id="aset-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 id="modal-title" class="text-xl font-bold text-gray-900">Tambah Aset Baru</h3>
            <button id="btn-close-modal" class="text-black cursor-pointer z-50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="mt-5">
            <form id="aset-form" method="POST" action="{{ route('data-aset.store') }}">
                @csrf
                <div id="method-field"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kode_aset" class="block text-sm font-medium text-gray-700">Kode Aset</label>
                        <input type="text" name="kode_aset" id="kode_aset" value="{{ old('kode_aset') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="nama_aset" class="block text-sm font-medium text-gray-700">Nama Aset</label>
                        <input type="text" name="nama_aset" id="nama_aset" value="{{ old('nama_aset') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                        <input type="text" name="kategori" id="kategori" value="{{ old('kategori') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="kondisi" class="block text-sm font-medium text-gray-700">Kondisi</label>
                        <select name="kondisi" id="kondisi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="tahun_pendagaan" class="block text-sm font-medium text-gray-700">Tahun Pengadaan</label>
                        <input type="number" name="tahun_pendagaan" id="tahun_pendagaan" value="{{ old('tahun_pendagaan') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="nilai_perolehan" class="block text-sm font-medium text-gray-700">Nilai Perolehan (Rp)</label>
                        <input type="number" name="nilai_perolehan" id="nilai_perolehan" value="{{ old('nilai_perolehan') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" id="btn-cancel-modal" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg">Batal</button>
                    <button type="submit" id="btn-submit-form" class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg">Simpan Aset</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT JAVASCRIPT UNTUK MODAL --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('aset-modal');
        const btnTambah = document.getElementById('btn-tambah-aset');
        const btnCloseModal = document.getElementById('btn-close-modal');
        const btnCancelModal = document.getElementById('btn-cancel-modal');
        const form = document.getElementById('aset-form');
        const modalTitle = document.getElementById('modal-title');
        const methodField = document.getElementById('method-field');
        const submitButton = document.getElementById('btn-submit-form');
        
        // Fungsi untuk menampilkan modal
        const openModal = () => modal.classList.remove('hidden');
        // Fungsi untuk menyembunyikan modal
        const closeModal = () => modal.classList.add('hidden');

        // Event listener untuk tombol 'Tambah Aset Baru'
        btnTambah.addEventListener('click', () => {
            form.reset(); // Mengosongkan form
            form.action = '{{ route("data-aset.store") }}'; // Set action ke route 'store'
            methodField.innerHTML = ''; // Hapus method spoofing jika ada
            modalTitle.textContent = 'Tambah Aset Baru';
            submitButton.textContent = 'Simpan Aset';
            document.getElementById('kode_aset').readOnly = false; // Kode aset bisa diisi
            document.getElementById('kode_aset').classList.remove('bg-gray-100');
            openModal();
        });

        // Event listener untuk semua tombol 'Edit'
        document.querySelectorAll('.btn-edit-aset').forEach(button => {
            button.addEventListener('click', function() {
                const assetData = JSON.parse(this.getAttribute('data-asset'));
                const assetId = this.getAttribute('data-id');

                form.reset();
                form.action = `/data-aset/${assetId}`; // Set action ke route 'update'
                methodField.innerHTML = '@method("PUT")'; // Tambahkan method spoofing untuk PUT
                modalTitle.textContent = 'Edit Data Aset';
                submitButton.textContent = 'Update Data';

                // Isi semua field form dengan data aset yang dipilih
                document.getElementById('kode_aset').value = assetData.kode_aset;
                document.getElementById('kode_aset').readOnly = true; // Kode aset tidak bisa diubah
                document.getElementById('kode_aset').classList.add('bg-gray-100');
                document.getElementById('nama_aset').value = assetData.nama_aset;
                document.getElementById('kategori').value = assetData.kategori;
                document.getElementById('kondisi').value = assetData.kondisi;
                document.getElementById('lokasi').value = assetData.lokasi;
                document.getElementById('tahun_pendagaan').value = assetData.tahun_pendagaan;
                document.getElementById('nilai_perolehan').value = assetData.nilai_perolehan;
                
                openModal();
            });
        });

        // Event listener untuk tombol close dan cancel
        btnCloseModal.addEventListener('click', closeModal);
        btnCancelModal.addEventListener('click', closeModal);

        // Jika ada pesan sukses, sembunyikan setelah 5 detik
        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.display = 'none';
            }, 5000);
        }

        @if ($errors->any())
            @if(old('id'))
            @else
                document.getElementById('btn-tambah-aset').click();
            @endif
        @endif
    });
</script>
@endsection

