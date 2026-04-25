<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_pemusnahan', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('kode_transaksi')->unique();
            $blueprint->foreignId('aset_id')->constrained('data_aset_kolektif');
            $blueprint->integer('jumlah_dimusnahkan');
            $blueprint->date('tanggal_pemusnahan');
            $blueprint->string('alasan_pemusnahan');
            $blueprint->string('metode_pemusnahan');
            $blueprint->string('penanggung_jawab');
            $blueprint->text('catatan')->nullable();
            $blueprint->string('dokumen_berita_acara')->nullable();
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_pemusnahan');
    }
};
