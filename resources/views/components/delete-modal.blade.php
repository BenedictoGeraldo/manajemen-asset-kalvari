@props(['actionUrl'])

<div x-data="{ show: false, deleteId: null, confirmText: '' }"
     @delete-modal.window="show = true; deleteId = $event.detail.id; confirmText = $event.detail.text || $event.detail.nomor"
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="show" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="show"
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Konfirmasi Hapus</h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus <strong x-text="confirmText"></strong>?</p>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button"
                        @click="
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ $actionUrl }}/' + deleteId;
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
                <button type="button" @click="show = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
