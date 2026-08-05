<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiMutasiAsetExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private $mutasis) {}

    public function collection()
    {
        return $this->mutasis;
    }

    public function headings(): array
    {
        return [
            'Nomor Mutasi',
            'Tanggal Mutasi',
            'Kode Aset',
            'Nama Aset',
            'Status',
            'Nama Pengaju',
            'Unit Pengaju',
            'Lokasi Lama',
            'Lokasi Baru',
            'Tanggal Diajukan',
            'Tanggal Disetujui',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Dibuat Pada',
        ];
    }

    public function map($mutasi): array
    {
        return [
            $mutasi->nomor_mutasi,
            $mutasi->tanggal_mutasi->format('d/m/Y'),
            $mutasi->aset?->kode_aset,
            $mutasi->aset?->nama_aset,
            ucfirst($mutasi->status),
            $mutasi->nama_pengaju ?? '-',
            $mutasi->unit_pengaju ?? '-',
            $mutasi->lokasiLama?->nama_lokasi ?? '-',
            $mutasi->lokasiBaru?->nama_lokasi ?? '-',
            $mutasi->tanggal_diajukan?->format('d/m/Y H:i') ?? '-',
            $mutasi->tanggal_disetujui?->format('d/m/Y H:i') ?? '-',
            $mutasi->tanggal_mulai?->format('d/m/Y H:i') ?? '-',
            $mutasi->tanggal_selesai?->format('d/m/Y H:i') ?? '-',
            $mutasi->created_at?->format('d/m/Y H:i') ?? '-',
        ];
    }
}
