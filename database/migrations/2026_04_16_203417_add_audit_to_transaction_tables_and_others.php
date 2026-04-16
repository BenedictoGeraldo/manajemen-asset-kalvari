<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'transaksi_mutasi_aset',
            'transaksi_pemeliharaan',
            'transaksi_peminjaman',
            'transaksi_peminjaman_items',
            'transaksi_pembelian_items',
            'menus',
            'permissions',
            'users'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'created_by')) {
                    $table->foreignId('created_by')->nullable()->constrained('users');
                }
                if (!Schema::hasColumn($tableName, 'updated_by')) {
                    $table->foreignId('updated_by')->nullable()->constrained('users');
                }
                if (!Schema::hasColumn($tableName, 'deleted_by')) {
                    $table->foreignId('deleted_by')->nullable()->constrained('users');
                }
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'transaksi_mutasi_aset',
            'transaksi_pemeliharaan',
            'transaksi_peminjaman',
            'transaksi_peminjaman_items',
            'transaksi_pembelian_items',
            'menus',
            'permissions',
            'users'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropForeign(['updated_by']);
                $table->dropForeign(['deleted_by']);
                $table->dropColumn(['created_by', 'updated_by', 'deleted_by']);
            });
        }
    }
};
