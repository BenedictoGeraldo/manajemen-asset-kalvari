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
        // Tabel data_aset tidak digunakan — data aset disimpan di data_aset_kolektif.
        // Migration ini di-skip jika tabel tidak ada.
        if (!Schema::hasTable('data_aset')) {
            return;
        }

        Schema::table('data_aset', function (Blueprint $table) {
            if (!Schema::hasColumn('data_aset', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('data_aset', 'updated_by')) {
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('data_aset', 'deleted_by')) {
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('data_aset', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('data_aset')) {
            return;
        }

        Schema::table('data_aset', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['created_by', 'updated_by', 'deleted_by', 'deleted_at']);
        });
    }
};
