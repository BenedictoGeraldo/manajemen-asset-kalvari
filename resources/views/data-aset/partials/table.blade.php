<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Aset</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Aset</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengelola</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departemen</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($asets as $aset)
                <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('data-aset.show', $aset->id) }}'">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $aset->kode_aset }}</div>
                        <div class="text-xs text-gray-500">{{ $aset->tahun_pengadaan }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $aset->nama_aset }}</div>
                        <div class="text-xs text-gray-500">{{ Str::limit($aset->deskripsi_aset, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 font-medium">{{ $aset->kategori->nama_kategori }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-700">{{ $aset->lokasi->nama_lokasi ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $aset->lokasi->sub_lokasi ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $aset->jumlah_barang }}
                        <span class="text-xs text-gray-500">({{ $aset->tipe_grup_v2 ?? $aset->tipe_grup }})</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 font-medium">{{ $aset->kondisi?->nama_kondisi ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-700">{{ $aset->pengelola->nama_pengelola ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $aset->pengelola->jabatan ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-700">
                            @if($aset->department)
                                {{ $aset->department->code }} - {{ $aset->department->name }}
                            @else
                                -
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation()">
                        <div class="flex space-x-2">
                            <a href="{{ route('data-aset.show', $aset->id) }}" data-navigate
                               class="action-view" title="Lihat Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('data-aset.edit'))
                            <a href="{{ route('data-aset.edit', $aset->id) }}" data-navigate
                               class="action-edit" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            @endif
                            @if(auth()->user()->is_super_admin || auth()->user()->hasPermission('data-aset.delete'))
                            <button type="button" @click="$dispatch('delete-modal', { id: {{ $aset->id }}, name: '{{ $aset->nama_aset }}' })" class="action-delete" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                            @endif

                            <a href="{{ route('data-aset.label', $aset->id) }}" target="_blank"
                               class="p-1 px-2 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 flex items-center" title="Cetak Label QR">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                QR Label
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                        Belum ada data aset
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Controls -->
<div class="px-6 py-4 bg-gray-50 border-t flex justify-between items-center">
    <div class="flex items-center">
        <select name="per_page" id="perPageSelect" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            @foreach([10, 25, 50, 100] as $size)
                <option value="{{ $size }}" {{ $asets->perPage() == $size ? 'selected' : '' }}>{{ $size }} / halaman</option>
            @endforeach
        </select>
    </div>
    <div id="paginationLinks">
        {{ $asets->appends(request()->except('page'))->links() }}
    </div>
</div>
