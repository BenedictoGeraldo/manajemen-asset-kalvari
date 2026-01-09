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
        Schema::create('data_aset_kolektif', function (Blueprint $table) {
            $table->id();

            // Informasi Dasar Aset
            $table->string('nama_aset');
            $table->foreignId('kategori_id')->constrained('master_kategori')->onDelete('restrict');
            $table->text('deskripsi_aset')->nullable();

            // Ukuran dan Bentuk
            $table->string('ukuran')->nullable();
            $table->text('deskripsi_ukuran_bentuk')->nullable();

            // Lokasi
            $table->foreignId('lokasi_id')->constrained('master_lokasi')->onDelete('restrict');

            // Kegunaan
            $table->string('kegunaan')->nullable();
            $table->text('keterangan_kegunaan')->nullable();

            // Jumlah dan Tipe
            $table->integer('jumlah_barang')->default(1);
            $table->enum('tipe_grup', ['individual', 'set', 'grup'])->default('individual');
            $table->text('keterangan_tipe_grup')->nullable();

            // Budget
            $table->decimal('budget', 15, 2)->nullable();
            $table->text('keterangan_budget')->nullable();

            // Pengelola
            $table->foreignId('pengelola_id')->constrained('master_pengelola')->onDelete('restrict');

            // Pengadaan
            $table->year('tahun_pengadaan');
            $table->decimal('nilai_pengadaan_total', 15, 2);
            $table->decimal('nilai_pengadaan_per_unit', 15, 2);

            // Kondisi
            $table->foreignId('kondisi_id')->constrained('master_kondisi')->onDelete('restrict');

            // Metadata
            $table->string('kode_aset')->unique()->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('nama_aset');
            $table->index('tahun_pengadaan');
            $table->index('kode_aset');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_aset_kolektif');
    }
};
