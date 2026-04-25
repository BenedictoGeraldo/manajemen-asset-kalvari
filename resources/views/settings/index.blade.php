@extends('layouts.main')

@section('title', 'Pengaturan Organisasi')
@section('page-title', 'Pengaturan Organisasi')

@section('content')
<div class="p-6">
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl space-y-8">
        @csrf

        @foreach($settings as $group => $items)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wider">
                        {{ $group == 'general' ? 'Profil Organisasi' : 'Branding Aplikasi' }}
                    </h3>
                </div>
                
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($items as $setting)
                            <div class="{{ $setting->type == 'textarea' || $setting->type == 'image' ? 'md:col-span-2' : '' }}">
                                <label for="{{ $setting->key }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $setting->label }}
                                </label>

                                @if($setting->type == 'text')
                                    <input type="text" name="{{ $setting->key }}" id="{{ $setting->key }}" 
                                           value="{{ $setting->value }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                @elseif($setting->type == 'textarea')
                                    <textarea name="{{ $setting->key }}" id="{{ $setting->key }}" rows="3"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">{{ $setting->value }}</textarea>
                                @elseif($setting->type == 'image')
                                    <div class="flex items-start space-x-6">
                                        <div class="flex-shrink-0">
                                            @if($setting->value)
                                                <img src="{{ $setting->value }}" alt="Logo preview" class="w-32 h-32 object-contain bg-gray-50 rounded-lg border border-gray-200">
                                            @else
                                                <div class="w-32 h-32 flex items-center justify-center bg-gray-100 rounded-lg border-2 border-dashed border-gray-300">
                                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <input type="file" name="{{ $setting->key }}" id="{{ $setting->key }}" accept="image/*"
                                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
                                            <p class="mt-2 text-xs text-gray-500">Pilih file logo dengan latar belakang transparan (PNG) untuk hasil terbaik.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <div class="flex justify-end pt-4">
            <button type="submit" class="btn-a group">
                <svg class="w-5 h-5 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
