@extends('layouts.main')

@section('title', 'Tambah Role')
@section('page-title', 'Tambah Role Baru')

@section('content')
<div class="p-6">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('roles.index') }}" data-navigate
                       class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        Manajemen Role
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Tambah Role</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Role</h3>

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Role <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-3 py-2 border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Permissions -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Hak Akses (Permissions)</h3>
                <p class="text-sm text-gray-600 mb-6">Pilih hak akses yang akan diberikan ke role ini</p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($permissions as $group => $groupPermissions)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="font-bold text-gray-700 mb-3 border-b pb-2 uppercase text-xs tracking-wider">{{ $group }}</h4>
                            <div class="space-y-2">
                                @foreach($groupPermissions as $permission)
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="perm-{{ $permission->id }}" name="permissions[]" type="checkbox" value="{{ $permission->id }}"
                                                   {{ (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'checked' : '' }}
                                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="perm-{{ $permission->id }}" class="font-medium text-gray-700 flex flex-col">
                                                <span>{{ $permission->display_name ?: $permission->name }}</span>
                                                @if($permission->description)
                                                    <span class="text-xs text-gray-500 font-normal mt-0.5">{{ $permission->description }}</span>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                <a href="{{ route('roles.index') }}" data-navigate
                   class="btn-c-outline">
                    Batal
                </a>
                <button type="submit"
                        class="btn-a-sm">
                    Simpan Role
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
