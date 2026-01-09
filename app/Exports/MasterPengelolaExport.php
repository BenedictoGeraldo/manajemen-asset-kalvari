<?php

namespace App\Exports;

use App\Models\MasterPengelola;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MasterPengelolaExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    public function collection()
    {
        return MasterPengelola::where('is_active', true)
            ->orderBy('nama_pengelola')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Pengelola',
            'Jabatan',
            'No. Telepon',
            'Email',
            'Deskripsi',
            'Status',
            'Tanggal Dibuat',
            'Terakhir Diupdate',
        ];
    }

    public function map($pengelola): array
    {
        return [
            $pengelola->id,
            $pengelola->nama_pengelola,
            $pengelola->jabatan ?? '-',
            $pengelola->no_telepon ?? '-',
            $pengelola->email ?? '-',
            $pengelola->deskripsi ?? '-',
            $pengelola->is_active ? 'Aktif' : 'Tidak Aktif',
            $pengelola->created_at->format('d/m/Y H:i'),
            $pengelola->updated_at->format('d/m/Y H:i'),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 30,  // Nama Pengelola
            'C' => 25,  // Jabatan
            'D' => 18,  // No. Telepon
            'E' => 30,  // Email
            'F' => 40,  // Deskripsi
            'G' => 12,  // Status
            'H' => 20,  // Tanggal Dibuat
            'I' => 20,  // Terakhir Diupdate
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
