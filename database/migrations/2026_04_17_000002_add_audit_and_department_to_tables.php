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
            'data_aset_kolektif',
            'master_kategori',
            'master_lokasi',
            'master_kondisi',
            'master_pengelola',
            'departments',
            'roles',
            'transaksi_pemusnahan'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'created_by')) {
                    $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                }
                if (!Schema::hasColumn($table->getTable(), 'updated_by')) {
                    $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
                }
                if (Schema::hasColumn($table->getTable(), 'deleted_at') && !Schema::hasColumn($table->getTable(), 'deleted_by')) {
                    $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
                }
            });
        }

        // Add department_id to data_aset_kolektif
        Schema::table('data_aset_kolektif', function (Blueprint $table) {
            if (!Schema::hasColumn('data_aset_kolektif', 'department_id')) {
                $table->foreignId('department_id')->nullable()->after('id')->constrained('departments')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_aset_kolektif', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });

        $tables = [
            'data_aset_kolektif',
            'master_kategori',
            'master_lokasi',
            'master_kondisi',
            'master_pengelola',
            'departments',
            'roles',
            'transaksi_pemusnahan'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropForeign(['updated_by']);
                if (Schema::hasColumn($table->getTable(), 'deleted_by')) {
                    $table->dropForeign(['deleted_by']);
                    $table->dropColumn('deleted_by');
                }
                $table->dropColumn(['created_by', 'updated_by']);
            });
        }
    }
};
