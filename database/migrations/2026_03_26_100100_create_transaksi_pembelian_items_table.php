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
        Schema::create('transaksi_pembelian_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_pembelian_id')->constrained('transaksi_pembelian')->onDelete('cascade');
            $table->string('nama_item');
            $table->foreignId('kategori_id')->constrained('master_kategori')->onDelete('restrict');
            $table->text('deskripsi')->nullable();
            $table->string('kegunaan');
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->foreignId('lokasi_id')->constrained('master_lokasi')->onDelete('restrict');
            $table->foreignId('kondisi_id')->constrained('master_kondisi')->onDelete('restrict');
            $table->foreignId('pengelola_id')->constrained('master_pengelola')->onDelete('restrict');
            $table->year('tahun_pengadaan');
            $table->text('catatan')->nullable();
            $table->foreignId('aset_kolektif_id')->nullable()->constrained('data_aset_kolektif')->nullOnDelete();
            $table->timestamps();

            $table->index('nama_item');
            $table->index('tahun_pengadaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pembelian_items');
    }
};
