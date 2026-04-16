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
            // Rename budget to nilai_budget
            $table->renameColumn('budget', 'nilai_budget');
            
            // Add new categorical columns
            $table->string('sumber_dana')->nullable()->after('budget');
            $table->string('label_penggunaan')->nullable()->after('sumber_dana');
            
            // Change ukuran to allow structured values
            $table->string('ukuran_label')->nullable()->after('ukuran');
            
            // We use string instead of enum to avoid doctrine/dbal dependency issues with ENUM change
            // and to allow more flexibility for the long strings from the image.
        });

        // Use DB statement to update tipe_grup if needed, or just handle in logic
        // For now, let's just add a comment or better yet, make it a string column for flexibility
        Schema::table('data_aset_kolektif', function (Blueprint $table) {
            $table->string('tipe_grup_v2')->nullable()->after('tipe_grup');
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
