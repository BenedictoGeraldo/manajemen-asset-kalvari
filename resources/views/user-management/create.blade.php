@extends('layouts.main')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User Baru')

@section('content')
<div class="p-6">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('user-management.index') }}" data-navigate
                       class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        Manajemen User
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Tambah User</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('user-management.store') }}" method="POST">
            @csrf

            <!-- Basic Info Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi User</h3>

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Role & Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="is_super_admin" name="is_super_admin" value="1" {{ old('is_super_admin') ? 'checked' : '' }}
                               x-data x-on:change="$dispatch('super-admin-changed', { checked: $event.target.checked })"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_super_admin" class="ml-2 text-sm text-gray-700">
                            Super Admin <span class="text-gray-500">(Akses penuh ke semua fitur)</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">
                            Status Aktif
                        </label>
                    </div>
                </div>
            </div>

            <!-- Permissions Section -->
            <div x-data="{ isSuperAdmin: {{ old('is_super_admin') ? 'true' : 'false' }} }"
                 x-on:super-admin-changed.window="isSuperAdmin = $event.detail.checked">
                <div x-show="!isSuperAdmin" class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengaturan Hak Akses</h3>
                    <p class="text-sm text-gray-600 mb-4">Pilih hak akses yang akan diberikan kepada user ini</p>

                    <!-- Tabel Penjelasan Permission -->
                    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-blue-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Penjelasan Hak Akses
                        </h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-blue-100">
                                    <tr>
                                        <th class="px-3 py-2 text-left font-semibold text-blue-900">Permission</th>
                                        <th class="px-3 py-2 text-left font-semibold text-blue-900">Akses Yang Diberikan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-blue-200">
                                    <tr class="bg-white">
                                        <td class="px-3 py-2 font-medium text-gray-700">Lihat Dashboard</td>
                                        <td class="px-3 py-2 text-gray-600">Dapat melihat halaman dashboard dengan statistik aset</td>
                                    </tr>
                                    <tr class="bg-blue-50">
                                        <td class="px-3 py-2 font-medium text-gray-700">Lihat Data Aset</td>
                                        <td class="px-3 py-2 text-gray-600">Dapat melihat daftar data aset (hanya baca, tidak bisa edit/hapus)</td>
                                    </tr>
                                    <tr class="bg-white">
                                        <td class="px-3 py-2 font-medium text-gray-700">Tambah Data Aset</td>
                                        <td class="px-3 py-2 text-gray-600">Dapat menambahkan data aset baru</td>
                                    </tr>
                                    <tr class="bg-blue-50">
                                        <td class="px-3 py-2 font-medium text-gray-700">Edit Data Aset</td>
                                        <td class="px-3 py-2 text-gray-600">Dapat mengedit data aset yang sudah ada</td>
                                    </tr>
                                    <tr class="bg-white">
                                        <td class="px-3 py-2 font-medium text-gray-700">Hapus Data Aset</td>
                                        <td class="px-3 py-2 text-gray-600">Dapat menghapus data aset</td>
                                    </tr>
                                    <tr class="bg-blue-50">
                                        <td class="px-3 py-2 font-medium text-gray-700">Export Data Aset</td>
                                        <td class="px-3 py-2 text-gray-600">Dapat mengekspor data aset ke Excel/CSV</td>
                                    </tr>
                                    <tr class="bg-white">
                                        <td class="px-3 py-2 font-medium text-gray-700">Lihat Master [X]</td>
                                        <td class="px-3 py-2 text-gray-600">Dapat melihat daftar master data (Kategori/Lokasi/Kondisi/Pengelola)</td>
                                    </tr>
                                    <tr class="bg-blue-50">
                                        <td class="px-3 py-2 font-medium text-gray-700">Tambah Master [X]</td>
                                        <td class="px-3 py-2 text-gray-600">Dapat menambah master data baru</td>
                                    </tr>
                                    <tr class="bg-white">
                                        <td class="px-3 py-2 font-medium text-gray-700">Edit Master [X]</td>
                                        <td class="px-3 py-2 text-gray-600">Dapat mengedit master data yang sudah ada</td>
                                    </tr>
                                    <tr class="bg-blue-50">
                                        <td class="px-3 py-2 font-medium text-gray-700">Hapus Master [X]</td>
                                        <td class="px-3 py-2 text-gray-600">Dapat menghapus master data</td>
                                    </tr>
                                    <tr class="bg-white">
                                        <td class="px-3 py-2 font-medium text-gray-700">Lihat Manajemen User</td>
                                        <td class="px-3 py-2 text-gray-600">Dapat melihat daftar user dan hak aksesnya</td>
                                    </tr>
                                    <tr class="bg-blue-50">
                                        <td class="px-3 py-2 font-medium text-gray-700">Tambah User</td>
                                        <td class="px-3 py-2 text-gray-600">Dapat menambah user baru dan mengatur hak aksesnya</td>
                                    </tr>
                                    <tr class="bg-white">
                                        <td class="px-3 py-2 font-medium text-gray-700">Edit User</td>
                                        <td class="px-3 py-2 text-gray-600">Dapat mengedit user dan mengubah hak aksesnya</td>
                                    </tr>
                                    <tr class="bg-blue-50">
                                        <td class="px-3 py-2 font-medium text-gray-700">Hapus User</td>
                                        <td class="px-3 py-2 text-gray-600">Dapat menghapus user dari sistem</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="mt-3 text-xs text-blue-700">
                            <strong>Catatan:</strong> Setiap permission bersifat independen. User hanya bisa melakukan aksi sesuai permission yang diberikan.
                        </p>
                    </div>

                    @error('permissions')
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="space-y-6">
                        @foreach($permissions as $group => $groupPermissions)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-md font-semibold text-gray-800">{{ $group }}</h4>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="select-all-group h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           data-group="{{ Str::slug($group) }}"
                                           onchange="toggleGroupPermissions(this)">
                                    <span class="ml-2 text-sm text-gray-600">Pilih Semua</span>
                                </label>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($groupPermissions as $permission)
                                <div class="flex items-center">
                                    <input type="checkbox" id="permission_{{ $permission->id }}"
                                           name="permissions[]" value="{{ $permission->id }}"
                                           class="permission-checkbox group-{{ Str::slug($group) }} h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                    <label for="permission_{{ $permission->id }}" class="ml-2 text-sm text-gray-700">
                                        {{ $permission->display_name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div x-show="isSuperAdmin" class="border-t pt-6">
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <div>
                                <h4 class="text-sm font-semibold text-purple-800">Super Admin memiliki akses penuh</h4>
                                <p class="text-xs text-purple-700 mt-1">User dengan role Super Admin dapat mengakses semua fitur tanpa pembatasan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                <a href="{{ route('user-management.index') }}" data-navigate
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleGroupPermissions(checkbox) {
    const group = checkbox.dataset.group;
    const checkboxes = document.querySelectorAll(`.group-${group}`);
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
}

// Check if all group checkboxes are checked to update "Select All"
document.addEventListener('DOMContentLoaded', function() {
    const groupCheckboxes = document.querySelectorAll('.permission-checkbox');
    groupCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const groupClass = Array.from(this.classList).find(cls => cls.startsWith('group-'));
            if (groupClass) {
                const group = groupClass.replace('group-', '');
                const allGroupCheckboxes = document.querySelectorAll(`.${groupClass}`);
                const selectAllCheckbox = document.querySelector(`[data-group="${group}"]`);

                if (selectAllCheckbox) {
                    const allChecked = Array.from(allGroupCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                }
            }
        });
    });
});
</script>
@endsection
