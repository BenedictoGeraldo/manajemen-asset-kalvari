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
        Schema::create('transaksi_pembelian', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pembelian')->unique();
            $table->date('tanggal_pembelian');
            $table->string('vendor_nama');
            $table->string('vendor_kontak')->nullable();
            $table->string('sumber_dana')->nullable();
            $table->enum('status', ['draft', 'diajukan', 'disetujui', 'dibatalkan'])->default('draft');
            $table->decimal('total_nilai', 15, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->boolean('is_posted_to_aset')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->index('nomor_pembelian');
            $table->index('tanggal_pembelian');
            $table->index('status');
            $table->index('vendor_nama');

            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pembelian');
    }
};
