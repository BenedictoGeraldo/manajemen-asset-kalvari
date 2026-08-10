@extends('layouts.main')

@section('title', 'Data Aset')
@section('page-title', 'Data Aset')

@section('content')
<div class="p-6">
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Header -->
    <div class="mb-6">
        <div class="space-y-4">
            <div class="w-full flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-2 sm:space-y-0">
                <!-- Search Input -->
                <div class="flex-1">
                    <div class="search-input-wrapper">
                        <input type="text" id="searchInput" value="{{ $search }}" placeholder="Cari aset berdasarkan nama, kode, kategori..." class="search-input-control flex-1 w-full px-4 py-3 border border-gray-300 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="off" />
                        <div class="search-submit-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3">
                    @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('data-aset.create'))
                    <a href="{{ route('data-aset.import.form') }}" class="btn-import">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Import
                    </a>
                    @endif
                    @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('data-aset.create'))
                    <a href="{{ route('data-aset.create') }}" data-navigate class="btn-a">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah
                    </a>
                    @endif
                </div>
            </div>

            <!-- Department Filters -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Departemen</label>
                    <select id="departmentFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Departemen</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>{{ $dept->code }} - {{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Sub Departemen</label>
                    <select id="subDepartmentFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed" {{ !$departmentId ? 'disabled' : '' }}>
                        <option value="">Sub Departemen</option>
                        @foreach($subDepartments as $sub)
                            <option value="{{ $sub->id }}" {{ $subDepartmentId == $sub->id ? 'selected' : '' }}>{{ $sub->code }} - {{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div id="tableContainer" class="bg-white rounded-lg shadow overflow-hidden relative">
        <div id="loadingOverlay" class="hidden absolute inset-0 bg-white bg-opacity-60 z-10">
            <div class="flex items-center justify-center h-full">
                <svg class="animate-spin h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
        @include('data-aset.partials.table')
    </div>

    @push('scripts')
    <script>
        (function() {
            const searchInput = document.getElementById('searchInput');
            const departmentFilter = document.getElementById('departmentFilter');
            const subDepartmentFilter = document.getElementById('subDepartmentFilter');
            const tableContainer = document.getElementById('tableContainer');
            const loadingOverlay = document.getElementById('loadingOverlay');

            if (!searchInput || !departmentFilter || !subDepartmentFilter || !tableContainer) return;

            let debounceTimer;

            function updateAsets(page = 1) {
                loadingOverlay.classList.remove('hidden');

                const search = searchInput.value;
                const departmentId = departmentFilter.value;
                const subDepartmentId = subDepartmentFilter.value;
                const perPage = document.getElementById('perPageSelect')?.value || 10;

                const url = new URL(window.location.href);
                url.searchParams.set('search', search);
                url.searchParams.set('department_id', departmentId);
                url.searchParams.set('sub_department_id', subDepartmentId);
                url.searchParams.set('per_page', perPage);
                url.searchParams.set('page', page);

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-Partial-Request': 'table'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const partials = document.createElement('div');
                    partials.innerHTML = html;

                    const newContent = partials.querySelector('.overflow-x-auto');
                    const newPaginate = partials.querySelector('.bg-gray-50.border-t');

                    const oldTable = tableContainer.querySelector('.overflow-x-auto');
                    const oldPaginate = tableContainer.querySelector('.bg-gray-50.border-t');

                    if (newContent && oldTable) oldTable.outerHTML = newContent.outerHTML;
                    if (newPaginate && oldPaginate) oldPaginate.outerHTML = newPaginate.outerHTML;

                    // Re-bind perPage select
                    bindPerPage();

                    window.history.pushState({}, '', url);
                    loadingOverlay.classList.add('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingOverlay.classList.add('hidden');
                });
            }

            function bindPerPage() {
                const perPageSelect = document.getElementById('perPageSelect');
                if (perPageSelect) {
                    perPageSelect.addEventListener('change', () => updateAsets(1));
                }

                // Re-bind pagination links
                document.querySelectorAll('#paginationLinks a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(this.href);
                        const page = url.searchParams.get('page');
                        updateAsets(page);
                    });
                });
            }

            // Search with debounce
            searchInput.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => updateAsets(1), 500);
            });

            // Department change
            departmentFilter.addEventListener('change', () => {
                const departmentId = departmentFilter.value;

                // Clear sub-department
                subDepartmentFilter.innerHTML = '<option value="">Semua Sub Departemen</option>';

                if (departmentId) {
                    subDepartmentFilter.disabled = false;
                    fetch(`{{ route('get-sub-departments') }}?parent_id=${departmentId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(sub => {
                                const option = document.createElement('option');
                                option.value = sub.id;
                                option.textContent = `${sub.code} - ${sub.name}`;
                                subDepartmentFilter.appendChild(option);
                            });
                        });
                } else {
                    subDepartmentFilter.disabled = true;
                }

                updateAsets(1);
            });

            // Sub Department change
            subDepartmentFilter.addEventListener('change', () => updateAsets(1));

            // Initial bind
            bindPerPage();
        })();
    </script>
    @endpush
</div>

<!-- Delete Confirmation Modal -->
<div x-data="{
    show: false,
    deleteId: null,
    assetName: ''
}"
@delete-modal.window="show = true; deleteId = $event.detail.id; assetName = $event.detail.name"
 x-show="show"
 x-cloak
 class="fixed inset-0 z-50 overflow-y-auto"
 aria-labelledby="modal-title"
 role="dialog"
 aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
             @click="show = false"
             aria-hidden="true"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Konfirmasi Hapus Aset
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Apakah Anda yakin ingin menghapus aset <strong x-text="assetName"></strong>?
                                Data yang sudah dihapus tidak dapat dikembalikan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button"
                        @click="
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ url('data-aset') }}/' + deleteId;
                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';
                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
                            form.appendChild(csrfInput);
                            form.appendChild(methodInput);
                            document.body.appendChild(form);
                            form.submit();
                        "
                        class="btn-danger-sm w-full inline-flex justify-center sm:ml-3 sm:w-auto sm:text-sm">
                    Hapus
                </button>
                <button type="button"
                        @click="show = false"
                        class="btn-c-outline mt-3 w-full inline-flex justify-center sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

