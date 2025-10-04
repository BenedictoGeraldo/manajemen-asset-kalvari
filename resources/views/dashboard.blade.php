{{-- Menggunakan layout utama yang sudah dibuat --}}
@extends('layouts.main')

{{-- Mengatur judul spesifik untuk halaman ini --}}
@section('title', 'Dashboard - Manajemen Aset')

{{-- Mendefinisikan konten yang akan dimasukkan ke dalam @yield('content') di layout utama --}}
@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

        <!-- Grid untuk Kartu Overview -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            <!-- Kartu Total Aset -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Aset
                            </dt>
                            <dd class="text-2xl font-bold text-gray-900">
                                {{ $totalAset ?? '0' }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Kartu Total Nilai Aset -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                         <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M12 14v3m-4.5-6.5H6v4h1.5v-4zM18 11.5h-1.5v4H18v-4z"/>
                         </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Nilai Aset (Rp)
                            </dt>
                            <dd class="text-2xl font-bold text-gray-900">
                                {{ number_format($totalNilai, 0, ',', '.') ?? '0' }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Kartu Aset Kondisi Baik -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 18.734V6a2 2 0 012-2h2.5" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Aset Kondisi Baik
                            </dt>
                            <dd class="text-2xl font-bold text-gray-900">
                                {{ $asetBaik ?? '0' }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

             <!-- Kartu Aset Perlu Perbaikan -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Perlu Perhatian
                            </dt>
                            <dd class="text-2xl font-bold text-gray-900">
                                {{ $asetPerluPerbaikan ?? '0' }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Kartu Jumlah Kategori -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Jumlah Kategori
                            </dt>
                            <dd class="text-2xl font-bold text-gray-900">
                                {{ $totalKategori ?? '0' }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <!-- Akhir Grid -->

    </div>
</div>
@endsection

