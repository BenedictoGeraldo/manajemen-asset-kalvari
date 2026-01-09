<?php

namespace App\Exports;

use App\Models\MasterLokasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MasterLokasiExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    public function collection()
    {
        return MasterLokasi::where('is_active', true)
            ->orderBy('nama_lokasi')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Lokasi',
            'Deskripsi',
            'Status',
            'Tanggal Dibuat',
            'Terakhir Diupdate',
        ];
    }

    public function map($lokasi): array
    {
        return [
            $lokasi->id,
            $lokasi->nama_lokasi,
            $lokasi->deskripsi ?? '-',
            $lokasi->is_active ? 'Aktif' : 'Tidak Aktif',
            $lokasi->created_at->format('d/m/Y H:i'),
            $lokasi->updated_at->format('d/m/Y H:i'),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 30,  // Nama Lokasi
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
