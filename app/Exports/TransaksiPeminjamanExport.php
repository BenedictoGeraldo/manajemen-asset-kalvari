<?php

namespace App\Exports;

use App\Models\TransaksiPeminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiPeminjamanExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return TransaksiPeminjaman::withCount('items')
            ->orderByDesc('tanggal_pengajuan')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nomor Peminjaman',
            'Tanggal Pengajuan',
            'Rencana Kembali',
            'Nama Peminjam',
            'Kontak Peminjam',
            'Unit Peminjam',
            'Jumlah Item',
            'Status',
            'Tanggal Disetujui',
            'Tanggal Serah Terima',
            'Tanggal Dikembalikan',
            'Tanggal Dibuat',
        ];
    }

    public function map($peminjaman): array
    {
        return [
            $peminjaman->nomor_peminjaman,
            optional($peminjaman->tanggal_pengajuan)->format('d/m/Y'),
            optional($peminjaman->tanggal_rencana_kembali)->format('d/m/Y') ?: '-',
            $peminjaman->nama_peminjam,
            $peminjaman->kontak_peminjam ?: '-',
            $peminjaman->unit_peminjam ?: '-',
            $peminjaman->items_count,
            ucfirst($peminjaman->status),
            optional($peminjaman->tanggal_disetujui)->format('d/m/Y H:i') ?: '-',
            optional($peminjaman->tanggal_serah_terima)->format('d/m/Y H:i') ?: '-',
            optional($peminjaman->tanggal_dikembalikan)->format('d/m/Y H:i') ?: '-',
            optional($peminjaman->created_at)->format('d/m/Y H:i'),
        ];
    }
}
