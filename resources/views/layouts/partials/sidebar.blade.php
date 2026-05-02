@php
    $isDashboardActive = request()->routeIs('dashboard');
    $isMasterActive = request()->routeIs('master.*');
    $isDataAsetActive = request()->routeIs('data-aset.*');
    $isTransaksiActive = request()->routeIs('transaksi.*');
    $isLaporanActive = request()->routeIs('laporan.*');
    $isPengaturanActive = request()->routeIs('user-management.*');
@endphp

<aside id="sidebar"
       :class="sidebarMinimized ? 'w-20' : 'w-64'"
       class="bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition md:translate-x-0">

    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
        <div class="flex items-center space-x-3 overflow-hidden">
            <div class="w-10 h-10 flex items-center justify-center flex-shrink-0">
                <img src="{{ setting('org_logo') ?: asset('logo-pelita-cross.png') }}" alt="{{ setting('org_name') }} Logo" class="w-15 h-15 object-contain">
            </div>
            <h1 x-show="!sidebarMinimized"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90"
                class="text-xl font-bold text-gray-800 whitespace-nowrap">PELITA</h1>
        </div>
        <button @click="sidebarMinimized = !sidebarMinimized"
                class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors duration-150 flex-shrink-0">
            <svg x-show="!sidebarMinimized" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
            <svg x-show="sidebarMinimized" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto py-4">
        <div class="px-3 space-y-1">
            @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('dashboard.view'))
            <a href="{{ route('dashboard') }}" data-navigate data-route="dashboard"
               :class="sidebarMinimized ? 'justify-center' : ''"
               class="nav-link flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 {{ $isDashboardActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}"
               :title="sidebarMinimized ? 'Dashboard' : ''">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span x-show="!sidebarMinimized" class="ml-3 whitespace-nowrap">Dashboard</span>
            </a>
            @endif

            @if(auth()->user()->is_super_admin || (!auth()->user()->isAdminDivisi() && auth()->user()->hasAnyPermission(['master.kategori.view', 'master.lokasi.view', 'master.kondisi.view', 'master.pengelola.view'])))
            <div class="relative" x-data="{ open: {{ $isMasterActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        data-sidebar-parent="master"
                        :class="sidebarMinimized ? 'justify-center' : 'justify-between'"
                        :title="sidebarMinimized ? 'Data Master' : ''"
                        class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 {{ $isMasterActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <div class="flex items-center" :class="sidebarMinimized ? '' : 'space-x-3'">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                        </svg>
                        <span x-show="!sidebarMinimized" class="whitespace-nowrap">Data Master</span>
                    </div>
                    <svg x-show="!sidebarMinimized" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div x-show="open && !sidebarMinimized" class="mt-1 ml-4 space-y-1">
                    @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('master.kategori.view'))
                    <a href="{{ route('master.kategori.index') }}" data-navigate data-route="master.kategori"
                       class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('master.kategori.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                        <span class="whitespace-nowrap">Kategori</span>
                    </a>
                    @endif

                    @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('master.lokasi.view'))
                    <a href="{{ route('master.lokasi.index') }}" data-navigate data-route="master.lokasi"
                       class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('master.lokasi.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span class="whitespace-nowrap">Lokasi</span>
                    </a>
                    @endif

                    @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('master.kondisi.view'))
                    <a href="{{ route('master.kondisi.index') }}" data-navigate data-route="master.kondisi"
                       class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('master.kondisi.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="whitespace-nowrap">Kondisi</span>
                    </a>
                    @endif

                    @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('master.pengelola.view'))
                    <a href="{{ route('master.pengelola.index') }}" data-navigate data-route="master.pengelola"
                       class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('master.pengelola.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        <span class="whitespace-nowrap">Pengelola</span>
                    </a>
                    @endif
                </div>
            </div>
            @endif

            @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('data-aset.view'))
            <a href="{{ route('data-aset.index') }}" data-navigate data-route="data-aset"
               :class="sidebarMinimized ? 'justify-center' : ''"
               :title="sidebarMinimized ? 'Data Aset' : ''"
               class="nav-link flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 {{ $isDataAsetActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span x-show="!sidebarMinimized" class="ml-3 whitespace-nowrap">Data Aset</span>
            </a>
            @endif

            @if(auth()->user()->is_super_admin || auth()->user()->hasAnyPermission(['transaksi.pembelian.view', 'transaksi.peminjaman.view', 'transaksi.pemeliharaan.view', 'transaksi.mutasi_aset.view']))
            <div class="relative" x-data="{ open: {{ $isTransaksiActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        data-sidebar-parent="transaksi"
                        :class="sidebarMinimized ? 'justify-center' : 'justify-between'"
                        :title="sidebarMinimized ? 'Data Transaksional' : ''"
                        class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 {{ $isTransaksiActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <div class="flex items-center" :class="sidebarMinimized ? '' : 'space-x-3'">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                        <span x-show="!sidebarMinimized" class="whitespace-nowrap">Data Transaksional</span>
                    </div>
                    <svg x-show="!sidebarMinimized" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>

                <div x-show="open && !sidebarMinimized" class="mt-1 ml-4 space-y-1">
                    @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pembelian.view'))
                    <a href="{{ route('transaksi.pembelian.index') }}" data-navigate data-route="transaksi-pembelian" class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('transaksi.pembelian.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        <span class="whitespace-nowrap">Pembelian</span>
                    </a>
                    @endif

                    @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.peminjaman.view'))
                    <a href="{{ route('transaksi.peminjaman.index') }}" data-navigate data-route="transaksi-peminjaman" class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('transaksi.peminjaman.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                        <span class="whitespace-nowrap">Peminjaman Aset</span>
                    </a>
                    @endif

                    @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.pemeliharaan.view'))
                    <a href="{{ route('transaksi.pemeliharaan.index') }}" data-navigate data-route="transaksi-pemeliharaan" class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('transaksi.pemeliharaan.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span class="whitespace-nowrap">Pemeliharaan</span>
                    </a>
                    @endif

                    @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('transaksi.mutasi_aset.view'))
                    <a href="{{ route('transaksi.mutasi_aset.index') }}" data-navigate data-route="transaksi-mutasi_aset" class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('transaksi.mutasi_aset.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                        <span class="whitespace-nowrap">Mutasi Aset</span>
                    </a>
                    @endif

                    <a href="{{ route('transaksi.pemusnahan.index') }}" data-navigate data-route="transaksi-pemusnahan" class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('transaksi.pemusnahan.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        <span class="whitespace-nowrap">Pemusnahan Aset</span>
                    </a>
                </div>
            </div>
            @endif

            @if(auth()->user()->is_super_admin)
            <div class="relative" x-data="{ open: {{ $isLaporanActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        data-sidebar-parent="laporan"
                        :class="sidebarMinimized ? 'justify-center' : 'justify-between'"
                        :title="sidebarMinimized ? 'Laporan' : ''"
                        class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 {{ $isLaporanActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <div class="flex items-center" :class="sidebarMinimized ? '' : 'space-x-3'">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span x-show="!sidebarMinimized" class="whitespace-nowrap">Laporan</span>
                    </div>
                    <svg x-show="!sidebarMinimized" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>

                <div x-show="open && !sidebarMinimized" class="mt-1 ml-4 space-y-1">
                    <a href="{{ route('laporan.data-aset.index') }}" data-navigate data-route="laporan-data-aset"
                       class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('laporan.data-aset.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6" /></svg>
                        <span class="whitespace-nowrap">Laporan Data Aset</span>
                    </a>
                    <a href="{{ route('laporan.mutasi-aset.index') }}" data-navigate data-route="laporan-mutasi-aset"
                       class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('laporan.mutasi-aset.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                        <span class="whitespace-nowrap">Laporan Mutasi Aset</span>
                    </a>
                    <a href="{{ route('laporan.pembelian.index') }}" data-navigate data-route="laporan-pembelian"
                       class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('laporan.pembelian.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4" /></svg>
                        <span class="whitespace-nowrap">Laporan Pembelian</span>
                    </a>
                    <a href="{{ route('laporan.pemusnahan.index') }}" data-navigate data-route="laporan-pemusnahan"
                       class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('laporan.pemusnahan.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        <span class="whitespace-nowrap">Laporan Pemusnahan</span>
                    </a>
                    <a href="{{ route('laporan.print-qr.index') }}" data-navigate data-route="print-qr"
                       class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('laporan.print-qr.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                        <span class="whitespace-nowrap">Cetak QR Code</span>
                    </a>
                </div>
            </div>
            @endif

            @if(auth()->user()->is_super_admin || (!auth()->user()->isAdminDivisi() && auth()->user()->hasPermission('user-management.view')))
            <div class="relative" x-data="{ open: {{ $isPengaturanActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        data-sidebar-parent="pengaturan"
                        :class="sidebarMinimized ? 'justify-center' : 'justify-between'"
                        :title="sidebarMinimized ? 'Pengaturan' : ''"
                        class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 {{ $isPengaturanActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <div class="flex items-center" :class="sidebarMinimized ? '' : 'space-x-3'">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span x-show="!sidebarMinimized" class="whitespace-nowrap">Pengaturan</span>
                    </div>
                    <svg x-show="!sidebarMinimized" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>

                <div x-show="open && !sidebarMinimized" class="mt-1 ml-4 space-y-1">
                    <a href="{{ route('user-management.index') }}" data-navigate data-route="user-management" class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('user-management.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <span class="whitespace-nowrap">Manajemen User</span>
                    </a>
                    <a href="{{ route('departments.index') }}" data-navigate data-route="departments" class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('departments.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        <span class="whitespace-nowrap">Manajemen Departemen</span>
                    </a>
                    <a href="{{ route('roles.index') }}" data-navigate data-route="roles" class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('roles.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        <span class="whitespace-nowrap">Manajemen Role</span>
                    </a>
                    <a href="{{ route('permissions.index') }}" data-navigate data-route="permissions" class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('permissions.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                        <span class="whitespace-nowrap">Daftar Hak Akses</span>
                    </a>
                    <a href="{{ route('settings.index') }}" data-navigate data-route="settings" class="nav-link submenu-link flex items-center px-4 py-2 text-sm rounded-lg transition-colors duration-150 {{ request()->routeIs('settings.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        <span class="whitespace-nowrap">Profil Organisasi</span>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </nav>

    <div class="p-4 border-t border-gray-200">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    :class="sidebarMinimized ? 'justify-center' : ''"
                    :title="sidebarMinimized ? 'Keluar Akun' : ''"
                    class="btn-danger-sm w-full flex items-center justify-center">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                <span x-show="!sidebarMinimized" class="ml-3 whitespace-nowrap">Keluar Akun</span>
            </button>
        </form>
    </div>
</aside>

<div id="sidebar-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 hidden md:hidden"></div>

<button id="mobile-sidebar-toggle" class="md:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-lg">
    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>

