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
        Schema::table('data_aset_kolektif', function (Blueprint $table) {
            $table->longText('gambar_aset_base64')->nullable()->after('deskripsi_aset');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_aset_kolektif', function (Blueprint $table) {
            $table->dropColumn('gambar_aset_base64');
        });
    }
};
