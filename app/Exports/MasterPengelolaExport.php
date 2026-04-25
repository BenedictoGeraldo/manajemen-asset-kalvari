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
        return MasterPengelola::with(['department'])
            ->where('is_active', true)
            ->orderBy('nama_pengelola')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kode Pengelola',
            'Nama Pengelola',
            'Jabatan',
            'Departemen',
            'Kontak/Telepon',
            'Email',
            'Status',
            'Tanggal Dibuat',
            'Terakhir Diupdate',
        ];
    }

    public function map($pengelola): array
    {
        return [
            $pengelola->id,
            $pengelola->kode_pengelola,
            $pengelola->nama_pengelola,
            $pengelola->jabatan ?? '-',
            $pengelola->department->name ?? '-',
            $pengelola->kontak ?? '-',
            $pengelola->email ?? '-',
            $pengelola->is_active ? 'Aktif' : 'Tidak Aktif',
            $pengelola->created_at->format('d/m/Y H:i'),
            $pengelola->updated_at->format('d/m/Y H:i'),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 15,  // Kode Pengelola
            'C' => 30,  // Nama Pengelola
            'D' => 25,  // Jabatan
            'E' => 25,  // Departemen
            'F' => 18,  // Kontak
            'G' => 30,  // Email
            'H' => 12,  // Status
            'I' => 20,  // Tanggal Dibuat
            'J' => 20,  // Terakhir Diupdate
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
