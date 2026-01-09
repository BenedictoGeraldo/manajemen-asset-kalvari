<?php

namespace App\Exports;

use App\Models\DataAsetKolektif;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DataAsetExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    public function collection()
    {
        return DataAsetKolektif::with(['kategori', 'lokasi', 'kondisi', 'pengelola'])
            ->orderBy('kode_aset')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Kode Aset',
            'Nama Aset',
            'Kategori',
            'Deskripsi Aset',
            'Ukuran',
            'Deskripsi Ukuran & Bentuk',
            'Lokasi',
            'Kegunaan',
            'Keterangan Kegunaan',
            'Jumlah Barang',
            'Tipe Grup',
            'Keterangan Tipe Grup',
            'Budget',
            'Keterangan Budget',
            'Pengelola',
            'Tahun Pengadaan',
            'Nilai Pengadaan Total',
            'Nilai Pengadaan Per Unit',
            'Kondisi',
            'Catatan',
            'Status',
            'Tanggal Dibuat',
            'Terakhir Diupdate',
        ];
    }

    public function map($aset): array
    {
        return [
            $aset->kode_aset ?? '-',
            $aset->nama_aset ?? '-',
            $aset->kategori->nama_kategori ?? '-',
            $aset->deskripsi_aset ?? '-',
            $aset->ukuran ?? '-',
            $aset->deskripsi_ukuran_bentuk ?? '-',
            $aset->lokasi->nama_lokasi ?? '-',
            $aset->kegunaan ?? '-',
            $aset->keterangan_kegunaan ?? '-',
            $aset->jumlah_barang ?? '-',
            $aset->tipe_grup ?? '-',
            $aset->keterangan_tipe_grup ?? '-',
            $aset->budget ? 'Rp ' . number_format($aset->budget, 0, ',', '.') : '-',
            $aset->keterangan_budget ?? '-',
            $aset->pengelola->nama_pengelola ?? '-',
            $aset->tahun_pengadaan ?? '-',
            $aset->nilai_pengadaan_total ? 'Rp ' . number_format($aset->nilai_pengadaan_total, 0, ',', '.') : '-',
            $aset->nilai_pengadaan_per_unit ? 'Rp ' . number_format($aset->nilai_pengadaan_per_unit, 0, ',', '.') : '-',
            $aset->kondisi->nama_kondisi ?? '-',
            $aset->catatan ?? '-',
            $aset->is_active ? 'Aktif' : 'Tidak Aktif',
            $aset->created_at ? $aset->created_at->format('d/m/Y H:i') : '-',
            $aset->updated_at ? $aset->updated_at->format('d/m/Y H:i') : '-',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,  // Kode Aset
            'B' => 35,  // Nama Aset
            'C' => 20,  // Kategori
            'D' => 40,  // Deskripsi Aset
            'E' => 20,  // Ukuran
            'F' => 35,  // Deskripsi Ukuran & Bentuk
            'G' => 30,  // Lokasi
            'H' => 25,  // Kegunaan
            'I' => 35,  // Keterangan Kegunaan
            'J' => 15,  // Jumlah Barang
            'K' => 15,  // Tipe Grup
            'L' => 30,  // Keterangan Tipe Grup
            'M' => 22,  // Budget
            'N' => 30,  // Keterangan Budget
            'O' => 30,  // Pengelola
            'P' => 18,  // Tahun Pengadaan
            'Q' => 25,  // Nilai Pengadaan Total
            'R' => 25,  // Nilai Pengadaan Per Unit
            'S' => 18,  // Kondisi
            'T' => 40,  // Catatan
            'U' => 12,  // Status
            'V' => 22,  // Tanggal Dibuat
            'W' => 22,  // Terakhir Diupdate
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '3B82F6']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }
}
