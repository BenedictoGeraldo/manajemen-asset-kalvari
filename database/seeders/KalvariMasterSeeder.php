<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterKategori;
use App\Models\MasterKondisi;
use Illuminate\Support\Facades\DB;

class KalvariMasterSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear existing generic master data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        MasterKategori::truncate();
        MasterKondisi::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Seed Master Kategori (A-K)
        $kategoriData = [
            ['nama_kategori' => 'A - FURNITURE', 'deskripsi' => 'Peralatan Mebel dan Perabotan'],
            ['nama_kategori' => 'B - PERALALATAN LITURGI', 'deskripsi' => 'Peralatan Ibadah dan Liturgi'],
            ['nama_kategori' => 'C - ELEKTRONIK', 'deskripsi' => 'Peralatan Elektronik'],
            ['nama_kategori' => 'D - ELEKTRIKAL', 'deskripsi' => 'Peralatan Elektrikal/Kelistrikan'],
            ['nama_kategori' => 'E - PAKAIAN', 'deskripsi' => 'Jubah, Seragam, dan Pakaian'],
            ['nama_kategori' => 'F - PERALATAN KEBERSIHAN', 'deskripsi' => 'Alat Kebersihan'],
            ['nama_kategori' => 'G - BAGIAN DARI BANGUNAN', 'deskripsi' => 'Kusen, Pintu, dsb'],
            ['nama_kategori' => 'H - TEMPAT PENYIMPANAN', 'deskripsi' => 'Rak, Lemari, dsb'],
            ['nama_kategori' => 'J - KENDARAAN', 'deskripsi' => 'Mobil, Motor, dsb'],
            ['nama_kategori' => 'K - OTHERS', 'deskripsi' => 'Lain-lain'],
        ];

        foreach ($kategoriData as $kategori) {
            MasterKategori::create($kategori);
        }

        // 3. Seed Master Kondisi (Standard Kalvari)
        $kondisiData = [
            ['nama_kondisi' => 'Baru, Belum di gunakan', 'keterangan' => 'Kondisi 100% baru', 'kode_warna' => 'green', 'urutan' => 1],
            ['nama_kondisi' => 'Berfungsi Normal', 'keterangan' => 'Berjalan dengan baik', 'kode_warna' => 'blue', 'urutan' => 2],
            ['nama_kondisi' => 'Berfungsi, perlu perbaikan', 'keterangan' => 'Bisa dipakai tapi ada kendala', 'kode_warna' => 'yellow', 'urutan' => 3],
            ['nama_kondisi' => 'Tidak Berfungsi, perlu perbaikan', 'keterangan' => 'Rusak tapi bisa diperbaiki', 'kode_warna' => 'orange', 'urutan' => 4],
            ['nama_kondisi' => 'Rusak', 'keterangan' => 'Sudah tidak bisa digunakan', 'kode_warna' => 'red', 'urutan' => 5],
        ];

        foreach ($kondisiData as $kondisi) {
            MasterKondisi::create($kondisi);
        }
    }
}
