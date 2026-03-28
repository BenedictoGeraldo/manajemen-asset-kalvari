<?php

namespace App\Exports;

use App\Models\TransaksiPembelian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiPembelianExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return TransaksiPembelian::withCount('items')
            ->orderByDesc('tanggal_pembelian')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nomor Pembelian',
            'Tanggal Pembelian',
            'Vendor',
            'Kontak Vendor',
            'Sumber Dana',
            'Jumlah Item',
            'Total Nilai',
            'Status',
            'Sudah Diposting Ke Aset',
            'Tanggal Dibuat',
        ];
    }

    public function map($pembelian): array
    {
        return [
            $pembelian->nomor_pembelian,
            optional($pembelian->tanggal_pembelian)->format('d/m/Y'),
            $pembelian->vendor_nama,
            $pembelian->vendor_kontak ?? '-',
            $pembelian->sumber_dana ?? '-',
            $pembelian->items_count,
            'Rp ' . number_format((float) $pembelian->total_nilai, 0, ',', '.'),
            ucfirst($pembelian->status),
            $pembelian->is_posted_to_aset ? 'Ya' : 'Belum',
            optional($pembelian->created_at)->format('d/m/Y H:i'),
        ];
    }
}
