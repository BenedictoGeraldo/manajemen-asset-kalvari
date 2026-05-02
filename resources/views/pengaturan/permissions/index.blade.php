@extends('layouts.main')

@section('title', 'Daftar Hak Akses')
@section('page-title', 'Daftar Hak Akses (Permissions)')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Hak Akses</h3>
        <p class="text-sm text-gray-600 mt-1">List semua hak akses yang tersedia dalam sistem</p>
    </div>

    <!-- Permissions Grid -->
    <div class="space-y-6">
        @foreach($permissions as $group => $subGroups)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                    <h3 class="font-bold text-gray-800 uppercase tracking-wider text-sm">{{ $group }}</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($subGroups as $subGroup => $perms)
                            <div class="permission-group bg-gray-50/50 rounded-lg p-4 border border-gray-100">
                                <h4 class="font-semibold text-gray-700 mb-3 border-b pb-2 text-xs tracking-wider">{{ $subGroup }}</h4>
                                <div class="space-y-3">
                                    @foreach($perms as $permission)
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900 leading-none">{{ $permission->display_name ?: $permission->name }}</p>
                                                <p class="text-xs text-gray-500 mt-1 font-mono">{{ $permission->slug }}</p>
                                                @if($permission->description)
                                                    <p class="text-xs text-gray-400 mt-1 italic">{{ $permission->description }}</p>
                                                @endif
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
@endsection
