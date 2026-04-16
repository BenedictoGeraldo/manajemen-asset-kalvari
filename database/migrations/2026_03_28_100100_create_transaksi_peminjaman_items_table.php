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
        Schema::create('transaksi_peminjaman_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_peminjaman_id');
            $table->unsignedBigInteger('data_aset_kolektif_id');

            $table->string('aset_kode_snapshot')->nullable();
            $table->string('aset_nama_snapshot');

            $table->unsignedInteger('jumlah')->default(1);

            $table->unsignedBigInteger('kondisi_awal_id')->nullable();
            $table->unsignedBigInteger('kondisi_akhir_id')->nullable();

            $table->text('catatan_item')->nullable();
            $table->text('catatan_serah_terima')->nullable();
            $table->text('catatan_pengembalian')->nullable();

            $table->timestamp('returned_at')->nullable();
            $table->timestamps();

            $table->foreign('transaksi_peminjaman_id', 'tp_item_peminjaman_fk')
                ->references('id')
                ->on('transaksi_peminjaman')
                ->cascadeOnDelete();

            $table->foreign('data_aset_kolektif_id', 'tp_item_aset_fk')
                ->references('id')
                ->on('data_aset_kolektif')
                ->restrictOnDelete();

            $table->foreign('kondisi_awal_id', 'tp_item_kondisi_awal_fk')
                ->references('id')
                ->on('master_kondisi')
                ->nullOnDelete();

            $table->foreign('kondisi_akhir_id', 'tp_item_kondisi_akhir_fk')
                ->references('id')
                ->on('master_kondisi')
                ->nullOnDelete();

            $table->index('transaksi_peminjaman_id');
            $table->index('data_aset_kolektif_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_peminjaman_items');
    }
};
