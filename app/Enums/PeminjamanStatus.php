<?php

namespace App\Enums;

enum PeminjamanStatus: string
{
    case DRAFT = 'draft';
    case DIAJUKAN = 'diajukan';
    case DISETUJUI = 'disetujui';
    case DITOLAK = 'ditolak';
    case DIPINJAM = 'dipinjam';
    case DIKEMBALIKAN = 'dikembalikan';
    case TERLAMBAT = 'terlambat';
    case DIBATALKAN = 'dibatalkan';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::DIAJUKAN => 'Diajukan',
            self::DISETUJUI => 'Disetujui',
            self::DITOLAK => 'Ditolak',
            self::DIPINJAM => 'Dipinjam',
            self::DIKEMBALIKAN => 'Dikembalikan',
            self::TERLAMBAT => 'Terlambat',
            self::DIBATALKAN => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'bg-gray-100 text-gray-700',
            self::DIAJUKAN => 'bg-yellow-100 text-yellow-800',
            self::DISETUJUI => 'bg-blue-100 text-blue-800',
            self::DITOLAK => 'bg-red-100 text-red-700',
            self::DIPINJAM => 'bg-indigo-100 text-indigo-800',
            self::DIKEMBALIKAN => 'bg-green-100 text-green-800',
            self::TERLAMBAT => 'bg-orange-100 text-orange-800',
            self::DIBATALKAN => 'bg-gray-200 text-gray-700',
        };
    }
}
