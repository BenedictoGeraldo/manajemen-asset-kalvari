<?php

namespace App\Exports;

use App\Models\MasterKondisi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MasterKondisiExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    public function collection()
    {
        return MasterKondisi::where('is_active', true)
            ->orderBy('nama_kondisi')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Kondisi',
            'Kode Warna',
            'Deskripsi',
            'Status',
            'Tanggal Dibuat',
            'Terakhir Diupdate',
        ];
    }

    public function map($kondisi): array
    {
        return [
            $kondisi->id,
            $kondisi->nama_kondisi,
            $kondisi->kode_warna ?? '-',
            $kondisi->deskripsi ?? '-',
            $kondisi->is_active ? 'Aktif' : 'Tidak Aktif',
            $kondisi->created_at->format('d/m/Y H:i'),
            $kondisi->updated_at->format('d/m/Y H:i'),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 25,  // Nama Kondisi
            'C' => 15,  // Kode Warna
            'D' => 40,  // Deskripsi
            'E' => 12,  // Status
            'F' => 20,  // Tanggal Dibuat
            'G' => 20,  // Terakhir Diupdate
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
