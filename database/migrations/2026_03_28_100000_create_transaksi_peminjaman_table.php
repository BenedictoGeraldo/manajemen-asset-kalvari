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
        Schema::create('transaksi_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_peminjaman')->unique();
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_rencana_kembali')->nullable();
            $table->timestamp('tanggal_disetujui')->nullable();
            $table->timestamp('tanggal_serah_terima')->nullable();
            $table->timestamp('tanggal_dikembalikan')->nullable();

            $table->string('nama_peminjam');
            $table->string('kontak_peminjam')->nullable();
            $table->string('unit_peminjam')->nullable();

            $table->enum('status', ['draft', 'diajukan', 'disetujui', 'ditolak', 'dipinjam', 'dikembalikan', 'terlambat', 'dibatalkan'])
                ->default('draft');

            $table->text('keperluan')->nullable();
            $table->text('catatan')->nullable();
            $table->text('catatan_approval')->nullable();
            $table->text('catatan_serah_terima')->nullable();
            $table->text('catatan_pengembalian')->nullable();

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('handover_by')->nullable();
            $table->unsignedBigInteger('returned_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->index('nomor_peminjaman');
            $table->index('tanggal_pengajuan');
            $table->index('tanggal_rencana_kembali');
            $table->index('status');
            $table->index('nama_peminjam');

            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('handover_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('returned_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_peminjaman');
    }
};
