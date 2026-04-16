<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanDataAsetExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Collection $laporanAset)
    {
    }

    public function collection(): Collection
    {
        return $this->laporanAset;
    }

    public function headings(): array
    {
        return [
            'Kode Aset',
            'Nama Aset',
            'Kategori',
            'Lokasi',
            'Kondisi',
            'Pengelola',
            'Jumlah Barang',
            'Tahun Pengadaan',
            'Nilai Per Unit',
            'Nilai Total',
            'Status',
        ];
    }

    public function map($aset): array
    {
        return [
            $aset->kode_aset ?? '-',
            $aset->nama_aset ?? '-',
            $aset->kategori?->nama_kategori ?? '-',
            $aset->lokasi?->nama_lokasi ?? '-',
            $aset->kondisi?->nama_kondisi ?? '-',
            $aset->pengelola?->nama_pengelola ?? '-',
            $aset->jumlah_barang ?? 0,
            $aset->tahun_pengadaan ?? '-',
            $aset->nilai_pengadaan_per_unit ? number_format((float) $aset->nilai_pengadaan_per_unit, 0, ',', '.') : '-',
            $aset->nilai_pengadaan_total ? number_format((float) $aset->nilai_pengadaan_total, 0, ',', '.') : '-',
            $aset->is_active ? 'Aktif' : 'Nonaktif',
        ];
    }
}
