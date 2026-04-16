<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_mutasi_aset', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_mutasi')->unique();
            $table->foreignId('data_aset_kolektif_id')->constrained('data_aset_kolektif')->restrictOnDelete();

            $table->date('tanggal_mutasi');
            $table->enum('jenis_mutasi', [
                'transfer_lokasi',
                'perubahan_kondisi',
                'write_off',
                'penghapusan',
            ]);
            $table->enum('status', [
                'draft',
                'diajukan',
                'disetujui',
                'ditolak',
                'proses',
                'selesai',
                'dibatalkan',
            ])->default('draft');

            // Lokasi transfer
            $table->foreignId('lokasi_lama_id')->nullable()->constrained('master_lokasi')->nullOnDelete();
            $table->foreignId('lokasi_baru_id')->nullable()->constrained('master_lokasi')->nullOnDelete();

            // Department transfer
            $table->foreignId('department_lama_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('department_baru_id')->nullable()->constrained('departments')->nullOnDelete();

            // Penanggung jawab transfer
            $table->foreignId('penanggung_jawab_lama_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('penanggung_jawab_baru_id')->nullable()->constrained('users')->nullOnDelete();

            // Kondisi
            $table->foreignId('kondisi_id')->nullable()->constrained('master_kondisi')->nullOnDelete();

            // Details
            $table->text('alasan')->nullable();
            $table->text('catatan')->nullable();
            $table->text('catatan_approval')->nullable();

            // Approval workflow
            $table->timestamp('tanggal_diajukan')->nullable();
            $table->timestamp('tanggal_disetujui')->nullable();
            $table->timestamp('tanggal_mulai')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();

            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('started_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();

            // Audit columns
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('nomor_mutasi');
            $table->index('tanggal_mutasi');
            $table->index('status');
            $table->index('jenis_mutasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_mutasi_aset');
    }
};
