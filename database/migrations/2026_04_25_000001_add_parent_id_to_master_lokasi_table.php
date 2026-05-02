<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_lokasi', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->foreign('parent_id')->references('id')->on('master_lokasi')->onDelete('cascade');

            // Kode lokasi sekarang hanya unik dalam konteks sub-lokasi,
            // hapus unique constraint lama jika ada dan buat nullable
            // (sudah nullable dari migration sebelumnya)
        });
    }

    public function down(): void
    {
        Schema::table('master_lokasi', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
