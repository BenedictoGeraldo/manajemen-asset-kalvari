<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_pemusnahan', function (Blueprint $table) {
            $table->string('nama_pengaju')->nullable()->after('penanggung_jawab');
            $table->string('unit_pengaju')->nullable()->after('nama_pengaju');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_pemusnahan', function (Blueprint $table) {
            $table->dropColumn(['nama_pengaju', 'unit_pengaju']);
        });
    }
};
