<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPemusnahanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $pemusnahans;

    public function __construct($pemusnahans)
    {
        $this->pemusnahans = $pemusnahans;
    }

    public function collection()
    {
        return $this->pemusnahans;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kode Transaksi',
            'Nama Aset',
            'Kode Aset',
            'Jumlah Dimusnahkan',
            'Tanggal Pemusnahan',
            'Alasan',
            'Metode',
            'Penanggung Jawab',
            'Catatan'
        ];
    }

    public function map($pemusnahan): array
    {
        return [
            $pemusnahan->id,
            $pemusnahan->kode_transaksi,
            $pemusnahan->aset?->nama_aset ?? '-',
            $pemusnahan->aset?->kode_aset ?? '-',
            $pemusnahan->jumlah_dimusnahkan,
            optional($pemusnahan->tanggal_pemusnahan)->format('d/m/Y') ?? '-',
            $pemusnahan->alasan_pemusnahan,
            $pemusnahan->metode_pemusnahan,
            $pemusnahan->penanggung_jawab,
            $pemusnahan->catatan
        ];
    }
}
