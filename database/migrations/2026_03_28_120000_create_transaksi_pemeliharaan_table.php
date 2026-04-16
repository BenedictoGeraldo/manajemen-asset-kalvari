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
        Schema::create('transaksi_pemeliharaan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pemeliharaan')->unique();
            $table->unsignedBigInteger('data_aset_kolektif_id');

            $table->date('tanggal_pengajuan');
            $table->date('tanggal_rencana')->nullable();
            $table->timestamp('tanggal_disetujui')->nullable();
            $table->timestamp('tanggal_mulai')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();

            $table->enum('jenis_pemeliharaan', ['rutin', 'perbaikan', 'darurat'])->default('rutin');
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])->default('sedang');
            $table->enum('status', ['draft', 'diajukan', 'disetujui', 'ditolak', 'proses', 'selesai', 'dibatalkan'])->default('draft');

            $table->unsignedBigInteger('kondisi_sebelum_id')->nullable();
            $table->unsignedBigInteger('kondisi_sesudah_id')->nullable();

            $table->string('vendor_nama')->nullable();
            $table->string('vendor_kontak')->nullable();

            $table->decimal('estimasi_biaya', 15, 2)->default(0);
            $table->decimal('realisasi_biaya', 15, 2)->default(0);

            $table->text('keluhan')->nullable();
            $table->text('tindakan')->nullable();
            $table->text('catatan')->nullable();
            $table->text('catatan_approval')->nullable();

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('started_by')->nullable();
            $table->unsignedBigInteger('completed_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->index('nomor_pemeliharaan');
            $table->index('tanggal_pengajuan');
            $table->index('status');
            $table->index('jenis_pemeliharaan');

            $table->foreign('data_aset_kolektif_id')->references('id')->on('data_aset_kolektif')->restrictOnDelete();
            $table->foreign('kondisi_sebelum_id')->references('id')->on('master_kondisi')->nullOnDelete();
            $table->foreign('kondisi_sesudah_id')->references('id')->on('master_kondisi')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('started_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('completed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pemeliharaan');
    }
};
