<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for better query performance
        Schema::table('data_aset_kolektif', function (Blueprint $table) {
            $this->addIndexIfNotExists('data_aset_kolektif', 'kategori_id', $table);
            $this->addIndexIfNotExists('data_aset_kolektif', 'lokasi_id', $table);
            $this->addIndexIfNotExists('data_aset_kolektif', 'kondisi_id', $table);
            $this->addIndexIfNotExists('data_aset_kolektif', 'pengelola_id', $table);
            $this->addIndexIfNotExists('data_aset_kolektif', 'tahun_pengadaan', $table);
            $this->addIndexIfNotExists('data_aset_kolektif', 'kode_aset', $table);
            $this->addIndexIfNotExists('data_aset_kolektif', ['is_active', 'created_at'], $table);
        });

        Schema::table('master_kategori', function (Blueprint $table) {
            $this->addIndexIfNotExists('master_kategori', 'is_active', $table);
        });

        Schema::table('master_lokasi', function (Blueprint $table) {
            $this->addIndexIfNotExists('master_lokasi', 'is_active', $table);
            $this->addIndexIfNotExists('master_lokasi', ['gedung', 'lantai'], $table);
        });

        Schema::table('master_kondisi', function (Blueprint $table) {
            $this->addIndexIfNotExists('master_kondisi', 'is_active', $table);
            $this->addIndexIfNotExists('master_kondisi', 'urutan', $table);
        });

        Schema::table('master_pengelola', function (Blueprint $table) {
            $this->addIndexIfNotExists('master_pengelola', 'is_active', $table);
        });
    }

    /**
     * Add index if it doesn't exist
     */
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_aset_kolektif', function (Blueprint $table) {
            $this->dropIndexIfExists('data_aset_kolektif', 'kategori_id', $table);
            $this->dropIndexIfExists('data_aset_kolektif', 'lokasi_id', $table);
            $this->dropIndexIfExists('data_aset_kolektif', 'kondisi_id', $table);
            $this->dropIndexIfExists('data_aset_kolektif', 'pengelola_id', $table);
            $this->dropIndexIfExists('data_aset_kolektif', 'tahun_pengadaan', $table);
            $this->dropIndexIfExists('data_aset_kolektif', 'kode_aset', $table);
            $this->dropIndexIfExists('data_aset_kolektif', ['is_active', 'created_at'], $table);
        });

        Schema::table('master_kategori', function (Blueprint $table) {
            $this->dropIndexIfExists('master_kategori', 'is_active', $table);
        });

        Schema::table('master_lokasi', function (Blueprint $table) {
            $this->dropIndexIfExists('master_lokasi', 'is_active', $table);
            $this->dropIndexIfExists('master_lokasi', ['gedung', 'lantai'], $table);
        });

        Schema::table('master_kondisi', function (Blueprint $table) {
            $this->dropIndexIfExists('master_kondisi', 'is_active', $table);
            $this->dropIndexIfExists('master_kondisi', 'urutan', $table);
        });

        Schema::table('master_pengelola', function (Blueprint $table) {
            $this->dropIndexIfExists('master_pengelola', 'is_active', $table);
        });
    }

    /**
     * Drop index if it exists
     */
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
