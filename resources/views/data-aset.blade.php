@extends('layouts.main')

@section('title', 'Data Aset - PELITA')

@section('content')
<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="mb-8 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-2xl p-6 text-white">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <h2 class="text-3xl font-bold flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Manajemen Data Aset
                </h2>
                <p class="text-purple-100 mt-2">Kelola seluruh data aset gereja</p>
            </div>
            <button id="btn-tambah-aset" class="bg-white hover:bg-gray-100 text-purple-600 font-bold py-3 px-6 rounded-xl transition-all duration-300 flex items-center shadow-xl transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Aset Baru
            </button>
        </div>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div id="success-alert" class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-lg shadow-md flex items-center" role="alert">
            <svg class="w-6 h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg shadow-md" role="alert">
            <div class="flex items-center mb-2">
                <svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="font-bold">Terjadi Kesalahan:</p>
            </div>
            <ul class="mt-2 list-disc list-inside ml-8">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Data Table Card -->
    <div class="bg-white overflow-hidden shadow-2xl rounded-2xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider w-20">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <button onclick="sortTable('nama_aset')" class="flex items-center hover:text-purple-600 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Nama Aset
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </button>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <button onclick="sortTable('kategori')" class="flex items-center hover:text-purple-600 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                Kategori
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </button>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <button onclick="sortTable('kondisi')" class="flex items-center hover:text-purple-600 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Kondisi
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </button>
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                            <button onclick="sortTable('nilai_perolehan')" class="flex items-center justify-end hover:text-purple-600 transition-colors ml-auto">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Nilai (Rp)
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </button>
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="aset-tbody">
                    @forelse ($asets as $index => $aset)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <span class="text-sm font-semibold text-gray-700">{{ $asets->firstItem() + $index }}</span>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ $aset->nama_aset }}</span>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-700">{{ $aset->kategori }}</span>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                @if ($aset->kondisi == 'Baik')
                                    <span class="px-2 py-1 inline-flex text-xs font-medium rounded bg-green-100 text-green-700">
                                        Baik
                                    </span>
                                @elseif ($aset->kondisi == 'Rusak Ringan')
                                    <span class="px-2 py-1 inline-flex text-xs font-medium rounded bg-yellow-100 text-yellow-700">
                                        Rusak Ringan
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs font-medium rounded bg-red-100 text-red-700">
                                        Rusak Berat
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                Rp {{ number_format($aset->nilai_perolehan, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center text-sm font-medium">
                                <button class="btn-edit-aset inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded transition-colors duration-200 mr-2"
                                        data-id="{{ $aset->id }}"
                                        data-asset='@json($aset)'>
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </button>
                                <form action="{{ route('data-aset.destroy', $aset) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset \'{{ $aset->nama_aset }}\'?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="mt-4 text-sm text-gray-600 font-medium">Belum ada data aset.</p>
                                <p class="text-xs text-gray-500 mt-1">Silakan tambahkan aset baru dengan klik tombol di atas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Link Paginasi --}}
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $asets->links() }}
        </div>
    </div>
</div>

<!-- Enhanced Modal -->
<div id="aset-modal" class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
    <div class="relative mx-auto p-8 border-0 w-full max-w-3xl shadow-2xl rounded-2xl bg-white animate-modal-slide-up">
        <div class="flex justify-between items-center border-b-2 border-purple-200 pb-4 mb-6">
            <h3 id="modal-title" class="text-2xl font-bold text-gray-900 flex items-center">
                <svg class="w-7 h-7 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Aset Baru
            </h3>
            <button id="btn-close-modal" class="text-gray-400 hover:text-gray-600 cursor-pointer transition-colors duration-200">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="mt-6">
            <form id="aset-form" method="POST" action="{{ route('data-aset.store') }}">
                @csrf
                <div id="method-field"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="nama_aset" class="block text-sm font-semibold text-gray-700 mb-2">Nama Aset</label>
                        <input type="text" name="nama_aset" id="nama_aset" value="{{ old('nama_aset') }}"
                               class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200"
                               placeholder="Nama aset" required>
                    </div>
                    <div>
                        <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                        <input type="text" name="kategori" id="kategori" value="{{ old('kategori') }}"
                               class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200"
                               placeholder="Contoh: Elektronik, Furniture" required>
                    </div>
                    <div>
                        <label for="kondisi" class="block text-sm font-semibold text-gray-700 mb-2">Kondisi</label>
                        <select name="kondisi" id="kondisi"
                                class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200" required>
                            <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>✓ Baik</option>
                            <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>⚠ Rusak Ringan</option>
                            <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>✗ Rusak Berat</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="lokasi" class="block text-sm font-semibold text-gray-700 mb-2">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}"
                               class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200"
                               placeholder="Contoh: Ruang Ibadah Utama" required>
                    </div>
                    <div>
                        <label for="tahun_pendagaan" class="block text-sm font-semibold text-gray-700 mb-2">Tahun Pengadaan</label>
                        <input type="number" name="tahun_pendagaan" id="tahun_pendagaan" value="{{ old('tahun_pendagaan') }}"
                               class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200"
                               placeholder="{{ date('Y') }}" min="1900" max="{{ date('Y') }}" required>
                    </div>
                    <div>
                        <label for="nilai_perolehan" class="block text-sm font-semibold text-gray-700 mb-2">Nilai Perolehan (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500 font-medium">Rp</span>
                            <input type="number" name="nilai_perolehan" id="nilai_perolehan" value="{{ old('nilai_perolehan') }}"
                                   class="block w-full pl-12 pr-4 py-3 rounded-lg border-2 border-gray-300 shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200"
                                   placeholder="0" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex justify-end space-x-3 border-t-2 border-gray-200 pt-6">
                    <button type="button" id="btn-cancel-modal"
                            class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition-all duration-200 flex items-center shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </button>
                    <button type="submit" id="btn-submit-form"
                            class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-lg transition-all duration-200 flex items-center shadow-md hover:shadow-lg transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Aset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes modal-slide-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-modal-slide-up {
        animation: modal-slide-up 0.3s ease-out;
    }
</style>

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

    // Fungsi untuk sorting table
    let currentSort = { column: null, direction: 'asc' };

    function sortTable(column) {
        const tbody = document.getElementById('aset-tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        // Toggle direction jika kolom yang sama
        if (currentSort.column === column) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort.column = column;
            currentSort.direction = 'asc';
        }

        // Get column index
        const columnMap = {
            'nama_aset': 1,
            'kategori': 2,
            'kondisi': 3,
            'nilai_perolehan': 4
        };
        const columnIndex = columnMap[column];

        // Sort rows
        rows.sort((a, b) => {
            let aValue = a.cells[columnIndex].textContent.trim();
            let bValue = b.cells[columnIndex].textContent.trim();

            // Handle nilai (remove Rp and formatting)
            if (column === 'nilai_perolehan') {
                aValue = parseInt(aValue.replace(/[^\d]/g, ''));
                bValue = parseInt(bValue.replace(/[^\d]/g, ''));
            }

            if (currentSort.direction === 'asc') {
                return aValue > bValue ? 1 : -1;
            } else {
                return aValue < bValue ? 1 : -1;
            }
        });

        // Re-append sorted rows
        rows.forEach(row => tbody.appendChild(row));
    }
</script>
@endsection

