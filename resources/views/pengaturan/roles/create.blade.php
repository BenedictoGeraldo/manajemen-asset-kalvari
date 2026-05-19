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
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Hak Akses (Permissions)</h3>
                        <p class="text-sm text-gray-600">Pilih hak akses yang akan diberikan ke role ini</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($permissions as $group => $subGroups)
                        <div class="permission-group-container bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-gray-50 to-white px-5 py-3 border-b border-gray-200 flex items-center justify-between">
                                <h3 class="font-bold text-gray-700 uppercase tracking-wider text-sm">{{ $group }}</h3>
                                <span class="permission-counter text-xs text-gray-500 font-medium"></span>
                            </div>
                            <div class="p-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($subGroups as $subGroup => $perms)
                                        <div class="permission-group bg-gray-50/60 rounded-xl p-4 border border-gray-100 hover:border-blue-200 transition-colors duration-200">
                                            <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-200">
                                                <h4 class="font-semibold text-gray-700 text-xs tracking-wider uppercase">{{ $subGroup }}</h4>
                                                <button type="button"
                                                        class="toggle-all-btn text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors"
                                                        data-group-target="{{ $subGroup }}">
                                                    Pilih Semua
                                                </button>
                                            </div>
                                            <div class="space-y-2.5">
                                                @foreach($perms as $permission)
                                                    @php
                                                        $checked = is_array(old('permissions')) && in_array($permission->id, old('permissions'));
                                                        $isView = str_ends_with($permission->name, '.view');
                                                    @endphp
                                                    <div class="flex items-center justify-between py-1.5 px-2 -mx-2 rounded-lg hover:bg-white transition-colors duration-150 cursor-pointer perm-row">
                                                        <div class="flex-1 min-w-0">
                                                            <div class="text-sm font-medium text-gray-700 flex items-center gap-1.5">
                                                                @if($isView)
                                                                    <svg class="w-3.5 h-3.5 text-yellow-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                    </svg>
                                                                @endif
                                                                <span class="truncate">{{ $permission->display_name ?: $permission->name }}</span>
                                                            </div>
                                                            @if($permission->description)
                                                                <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $permission->description }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="flex-shrink-0 ml-3">
                                                            <input type="checkbox" id="perm-{{ $permission->id }}" name="permissions[]"
                                                                   value="{{ $permission->id }}" data-name="{{ $permission->name }}"
                                                                   {{ $checked ? 'checked' : '' }}
                                                                   class="sr-only perm-checkbox">
                                                            <div class="toggle-track w-9 h-5 rounded-full cursor-pointer transition-colors duration-200 {{ $checked ? 'bg-blue-600' : 'bg-gray-300' }}"
                                                                 onclick="const cb = this.parentElement.querySelector('.perm-checkbox'); cb.checked = !cb.checked; cb.dispatchEvent(new Event('change', {bubbles:true}));">
                                                                <div class="toggle-dot w-4 h-4 bg-white rounded-full shadow transition-transform duration-200 {{ $checked ? 'translate-x-4' : 'translate-x-0.5' }}"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function syncToggle(cb) {
        const track = cb.closest('.flex-shrink-0').querySelector('.toggle-track');
        const dot = track.querySelector('.toggle-dot');
        if (cb.checked) {
            track.classList.add('bg-blue-600');
            track.classList.remove('bg-gray-300');
            dot.classList.add('translate-x-4');
            dot.classList.remove('translate-x-0.5');
        } else {
            track.classList.remove('bg-blue-600');
            track.classList.add('bg-gray-300');
            dot.classList.remove('translate-x-4');
            dot.classList.add('translate-x-0.5');
        }
    }

    document.querySelectorAll('.perm-checkbox').forEach(syncToggle);

    document.querySelectorAll('.perm-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            syncToggle(this);
        });
    });

    const groups = document.querySelectorAll('.permission-group');

    groups.forEach(group => {
        const checkboxes = group.querySelectorAll('.perm-checkbox');
        let viewCheckbox = null;

        checkboxes.forEach(cb => {
            if (cb.dataset.name && cb.dataset.name.endsWith('.view')) {
                viewCheckbox = cb;
            }
        });

        if (viewCheckbox) {
            viewCheckbox.addEventListener('change', function() {
                if (!this.checked) {
                    checkboxes.forEach(cb => {
                        if (cb !== viewCheckbox) {
                            cb.checked = false;
                            syncToggle(cb);
                        }
                    });
                }
            });

            checkboxes.forEach(cb => {
                if (cb !== viewCheckbox) {
                    cb.addEventListener('change', function() {
                        if (this.checked) {
                            viewCheckbox.checked = true;
                            syncToggle(viewCheckbox);
                        }
                    });
                }
            });
        }
    });

    document.querySelectorAll('.toggle-all-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const groupEl = this.closest('.permission-group');
            const checkboxes = groupEl.querySelectorAll('.perm-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);

            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
                syncToggle(cb);
                cb.dispatchEvent(new Event('change', { bubbles: true }));
            });

            this.textContent = allChecked ? 'Pilih Semua' : 'Hapus Semua';
        });
    });

    document.querySelectorAll('.permission-group-container').forEach(container => {
        const counterEl = container.querySelector('.permission-counter');
        if (!counterEl) return;

        const allCheckboxes = container.querySelectorAll('.perm-checkbox');
        function refreshCounter() {
            const checked = container.querySelectorAll('.perm-checkbox:checked').length;
            const total = allCheckboxes.length;
            counterEl.textContent = checked + ' / ' + total + ' dipilih';
        }

        allCheckboxes.forEach(cb => cb.addEventListener('change', refreshCounter));
        refreshCounter();
    });

    document.querySelectorAll('.permission-group').forEach(group => {
        const btn = group.querySelector('.toggle-all-btn');
        const checkboxes = group.querySelectorAll('.perm-checkbox');
        if (!btn) return;

        function refreshBtn() {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            btn.textContent = allChecked ? 'Hapus Semua' : 'Pilih Semua';
        }

        checkboxes.forEach(cb => cb.addEventListener('change', refreshBtn));
        refreshBtn();
    });

    document.querySelectorAll('.perm-row').forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.closest('.toggle-track')) return;
            const cb = this.querySelector('.perm-checkbox');
            cb.checked = !cb.checked;
            syncToggle(cb);
            cb.dispatchEvent(new Event('change', { bubbles: true }));
        });
    });
});
</script>
@endpush
@endsection
