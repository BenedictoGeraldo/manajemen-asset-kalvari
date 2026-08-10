@props([
    'title' => 'Konfirmasi',
    'message' => '',
    'confirmLabel' => 'Konfirmasi',
    'cancelLabel' => 'Batal',
    'confirmButtonClass' => 'btn-a-sm',
    'iconType' => 'info',
    'event' => 'confirm-modal',
    'actionUrl',
    'actionSuffix',
    'textareaName' => null,
    'textareaLabel' => 'Catatan',
    'textareaRequired' => false,
    'textareaPlaceholder' => 'Tulis catatan...',
])

@php
    $iconColors = [
        'success' => 'bg-green-100',
        'danger' => 'bg-red-100',
        'warning' => 'bg-yellow-100',
        'info' => 'bg-blue-100',
    ];
    $iconTextColors = [
        'success' => 'text-green-600',
        'danger' => 'text-red-600',
        'warning' => 'text-yellow-600',
        'info' => 'text-blue-600',
    ];
    $iconSvgPaths = [
        'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />',
        'danger' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
        'info' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
    ];
    $iconColor = $iconColors[$iconType] ?? $iconColors['info'];
    $iconTextColor = $iconTextColors[$iconType] ?? $iconTextColors['info'];
    $iconSvg = $iconSvgPaths[$iconType] ?? $iconSvgPaths['info'];
@endphp

<div x-data="{ show: false, recordId: null, recordNomor: '', catatan: '' }"
     @{{ $event }}.window="show = true; recordId = $event.detail.id; recordNomor = $event.detail.nomor; catatan = ''"
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="confirm-modal-title"
     role="dialog"
     aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
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

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form method="POST" x-bind:action="'{{ $actionUrl }}/' + recordId + '{{ $actionSuffix }}'">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full {{ $iconColor }} sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 {{ $iconTextColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $iconSvg !!}
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="confirm-modal-title">
                                {{ $title }}
                            </h3>
                            <div class="mt-3">
                                <p class="text-sm text-gray-500 mb-3">{!! $message !!}</p>
                                @if($textareaName)
                                <div>
                                    <label for="{{ $textareaName }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $textareaLabel }} @if($textareaRequired)<span class="text-red-500">*</span>@endif</label>
                                    <textarea id="{{ $textareaName }}" name="{{ $textareaName }}" rows="3" x-model="catatan"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                                              placeholder="{{ $textareaPlaceholder }}" {{ $textareaRequired ? 'required' : '' }}></textarea>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                            @if($textareaRequired) :disabled="!catatan.trim()" @endif
                            @if($textareaRequired) :class="catatan.trim() ? '{{ $confirmButtonClass }}' : 'btn-c-outline opacity-50 cursor-not-allowed'" @else class="{{ $confirmButtonClass }}" @endif
                            class="w-full inline-flex justify-center sm:ml-3 sm:w-auto sm:text-sm">
                        {{ $confirmLabel }}
                    </button>
                    <button type="button"
                            @click="show = false"
                            class="btn-c-outline mt-3 w-full inline-flex justify-center sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ $cancelLabel }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
