@extends('layouts.main')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

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
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Edit User</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    @if($user->is_super_admin && $user->id === auth()->id())
    <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">Perhatian:</span>
            <span class="ml-1">Anda sedang mengedit akun Anda sendiri sebagai Super Admin. Berhati-hatilah saat mengubah hak akses.</span>
        </div>
    </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('user-management.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Basic Info Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi User</h3>

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
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
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password (Optional) -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-blue-800 mb-3">
                        <strong>Ubah Password:</strong> Kosongkan jika tidak ingin mengubah password
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password Baru
                            </label>
                            <input type="password" id="password" name="password"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Role & Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="is_super_admin" name="is_super_admin" value="1"
                               {{ old('is_super_admin', $user->is_super_admin) ? 'checked' : '' }}
                               {{ $user->id === auth()->id() ? 'disabled' : '' }}
                               x-data x-on:change="$dispatch('super-admin-changed', { checked: $event.target.checked })"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_super_admin" class="ml-2 text-sm text-gray-700">
                            Super Admin <span class="text-gray-500">(Akses penuh ke semua fitur)</span>
                            @if($user->id === auth()->id())
                                <span class="text-yellow-600 text-xs">(Tidak dapat diubah pada akun sendiri)</span>
                            @endif
                        </label>
                        @if($user->id === auth()->id())
                            <input type="hidden" name="is_super_admin" value="{{ $user->is_super_admin ? '1' : '0' }}">
                        @endif
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">
                            Status Aktif
                        </label>
                    </div>
                </div>
            </div>

            <!-- Permissions Section -->
            <div x-data="{ isSuperAdmin: {{ old('is_super_admin', $user->is_super_admin) ? 'true' : 'false' }} }"
                 x-on:super-admin-changed.window="isSuperAdmin = $event.detail.checked">
                <div x-show="!isSuperAdmin" class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengaturan Role & Divisi</h3>
                    <p class="text-sm text-gray-600 mb-4">Pilih role dan departemen divisi user ini bekerja</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Role User <span class="text-red-500">*</span>
                            </label>
                            <select id="role_id" name="role_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Departemen / Divisi <span class="text-red-500">*</span>
                            </label>
                            <select id="department_id" name="department_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih Departemen --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
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
                   class="btn-c-outline">
                    Batal
                </a>
                <button type="submit"
                        class="btn-a-sm">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

