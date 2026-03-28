<?php

namespace App\Exports;

use App\Models\TransaksiPemeliharaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiPemeliharaanExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return TransaksiPemeliharaan::with(['aset'])
            ->orderByDesc('tanggal_pengajuan')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nomor Pemeliharaan',
            'Tanggal Pengajuan',
            'Aset',
            'Kode Aset',
            'Jenis Pemeliharaan',
            'Prioritas',
            'Status',
            'Vendor',
            'Estimasi Biaya',
            'Realisasi Biaya',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Tanggal Dibuat',
        ];
    }

    public function map($pemeliharaan): array
    {
        return [
            $pemeliharaan->nomor_pemeliharaan,
            optional($pemeliharaan->tanggal_pengajuan)->format('d/m/Y'),
            $pemeliharaan->aset->nama_aset ?? '-',
            $pemeliharaan->aset->kode_aset ?? '-',
            ucfirst($pemeliharaan->jenis_pemeliharaan),
            ucfirst($pemeliharaan->prioritas),
            ucfirst($pemeliharaan->status),
            $pemeliharaan->vendor_nama ?: '-',
            'Rp ' . number_format((float) $pemeliharaan->estimasi_biaya, 0, ',', '.'),
            'Rp ' . number_format((float) $pemeliharaan->realisasi_biaya, 0, ',', '.'),
            optional($pemeliharaan->tanggal_mulai)->format('d/m/Y H:i') ?: '-',
            optional($pemeliharaan->tanggal_selesai)->format('d/m/Y H:i') ?: '-',
            optional($pemeliharaan->created_at)->format('d/m/Y H:i'),
        ];
    }
}
