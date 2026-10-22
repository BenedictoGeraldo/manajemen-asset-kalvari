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
        Schema::table('data_aset_kolektif', function (Blueprint $table) {
            // Rename budget to nilai_budget if budget exists
            if (Schema::hasColumn('data_aset_kolektif', 'budget')) {
                $table->renameColumn('budget', 'nilai_budget');
            }
            
            // Add new categorical columns
            if (!Schema::hasColumn('data_aset_kolektif', 'sumber_dana')) {
                $table->string('sumber_dana')->nullable()->after('nilai_budget');
            }
            if (!Schema::hasColumn('data_aset_kolektif', 'label_penggunaan')) {
                $table->string('label_penggunaan')->nullable()->after('sumber_dana');
            }
            
            // Change ukuran to allow structured values
            if (!Schema::hasColumn('data_aset_kolektif', 'ukuran_label')) {
                $table->string('ukuran_label')->nullable()->after('ukuran');
            }
        });

        Schema::table('data_aset_kolektif', function (Blueprint $table) {
            if (!Schema::hasColumn('data_aset_kolektif', 'tipe_grup_v2')) {
                $table->string('tipe_grup_v2')->nullable()->after('tipe_grup');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_aset_kolektif', function (Blueprint $table) {
            $table->renameColumn('nilai_budget', 'budget');
            $table->dropColumn(['sumber_dana', 'label_penggunaan', 'ukuran_label', 'tipe_grup_v2']);
        });
    }
};
