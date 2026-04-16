<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambah index pada tabel-tabel transaksi untuk mempercepat query filter & sorting.
     */
    public function up(): void
    {
        Schema::table('transaksi_peminjaman', function (Blueprint $table) {
            $this->addIndexIfNotExists('transaksi_peminjaman', 'status', $table);
            $this->addIndexIfNotExists('transaksi_peminjaman', 'tanggal_pengajuan', $table);
            $this->addIndexIfNotExists('transaksi_peminjaman', 'tanggal_rencana_kembali', $table);
        });

        Schema::table('transaksi_pembelian', function (Blueprint $table) {
            $this->addIndexIfNotExists('transaksi_pembelian', 'status', $table);
            $this->addIndexIfNotExists('transaksi_pembelian', 'tanggal_pembelian', $table);
            $this->addIndexIfNotExists('transaksi_pembelian', 'nomor_pembelian', $table);
        });

        Schema::table('transaksi_pemeliharaan', function (Blueprint $table) {
            $this->addIndexIfNotExists('transaksi_pemeliharaan', 'status', $table);
            $this->addIndexIfNotExists('transaksi_pemeliharaan', 'tanggal_pengajuan', $table);
            $this->addIndexIfNotExists('transaksi_pemeliharaan', 'data_aset_kolektif_id', $table);
        });

        Schema::table('transaksi_mutasi_aset', function (Blueprint $table) {
            $this->addIndexIfNotExists('transaksi_mutasi_aset', 'status', $table);
            $this->addIndexIfNotExists('transaksi_mutasi_aset', 'tanggal_mutasi', $table);
            $this->addIndexIfNotExists('transaksi_mutasi_aset', 'jenis_mutasi', $table);
            $this->addIndexIfNotExists('transaksi_mutasi_aset', 'data_aset_kolektif_id', $table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_peminjaman', function (Blueprint $table) {
            $this->dropIndexIfExists('transaksi_peminjaman', 'status', $table);
            $this->dropIndexIfExists('transaksi_peminjaman', 'tanggal_pengajuan', $table);
            $this->dropIndexIfExists('transaksi_peminjaman', 'tanggal_rencana_kembali', $table);
        });

        Schema::table('transaksi_pembelian', function (Blueprint $table) {
            $this->dropIndexIfExists('transaksi_pembelian', 'status', $table);
            $this->dropIndexIfExists('transaksi_pembelian', 'tanggal_pembelian', $table);
            $this->dropIndexIfExists('transaksi_pembelian', 'nomor_pembelian', $table);
        });

        Schema::table('transaksi_pemeliharaan', function (Blueprint $table) {
            $this->dropIndexIfExists('transaksi_pemeliharaan', 'status', $table);
            $this->dropIndexIfExists('transaksi_pemeliharaan', 'tanggal_pengajuan', $table);
            $this->dropIndexIfExists('transaksi_pemeliharaan', 'data_aset_kolektif_id', $table);
        });

        Schema::table('transaksi_mutasi_aset', function (Blueprint $table) {
            $this->dropIndexIfExists('transaksi_mutasi_aset', 'status', $table);
            $this->dropIndexIfExists('transaksi_mutasi_aset', 'tanggal_mutasi', $table);
            $this->dropIndexIfExists('transaksi_mutasi_aset', 'jenis_mutasi', $table);
            $this->dropIndexIfExists('transaksi_mutasi_aset', 'data_aset_kolektif_id', $table);
        });
    }

    private function addIndexIfNotExists(string $table, string|array $columns, Blueprint $blueprint): void
    {
        $indexName = is_array($columns)
            ? $table . '_' . implode('_', $columns) . '_index'
            : $table . '_' . $columns . '_index';

        $indexes = DB::select("SHOW INDEXES FROM {$table} WHERE Key_name = ?", [$indexName]);

        if (empty($indexes)) {
            $blueprint->index($columns);
        }
    }

    private function dropIndexIfExists(string $table, string|array $columns, Blueprint $blueprint): void
    {
        $indexName = is_array($columns)
            ? $table . '_' . implode('_', $columns) . '_index'
            : $table . '_' . $columns . '_index';

        $indexes = DB::select("SHOW INDEXES FROM {$table} WHERE Key_name = ?", [$indexName]);

        if (!empty($indexes)) {
            $blueprint->dropIndex($indexName);
        }
    }
};
