<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE transaksi_mutasi_aset MODIFY COLUMN jenis_mutasi ENUM('transfer_lokasi', 'perubahan_kondisi', 'write_off') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transaksi_mutasi_aset MODIFY COLUMN jenis_mutasi ENUM('transfer_lokasi', 'perubahan_kondisi', 'write_off', 'penghapusan') NOT NULL");
    }
};
