<?php

namespace App\Exports;

use App\Models\MasterKategori;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MasterKategoriExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    public function collection()
    {
        return MasterKategori::where('is_active', true)
            ->orderBy('nama_kategori')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Kategori',
            'Deskripsi',
            'Status',
            'Tanggal Dibuat',
            'Terakhir Diupdate',
        ];
    }

    public function map($kategori): array
    {
        return [
            $kategori->id,
            $kategori->nama_kategori,
            $kategori->deskripsi ?? '-',
            $kategori->is_active ? 'Aktif' : 'Tidak Aktif',
            $kategori->created_at->format('d/m/Y H:i'),
            $kategori->updated_at->format('d/m/Y H:i'),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 30,  // Nama Kategori
            'C' => 40,  // Deskripsi
            'D' => 12,  // Status
            'E' => 20,  // Tanggal Dibuat
            'F' => 20,  // Terakhir Diupdate
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
