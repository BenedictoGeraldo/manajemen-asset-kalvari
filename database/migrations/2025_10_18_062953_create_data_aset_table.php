<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('data_aset')) {
            Schema::create('data_aset', function (Blueprint $table) {
                $table->id();
                $table->string('nama_aset');
                $table->string('kategori')->nullable();
                $table->text('deskripsi_aset')->nullable();
                $table->string('ukuran')->nullable();
                $table->text('deskripsi_ukuran_dan_bentuk')->nullable();
                $table->string('lokasi')->nullable();
                $table->string('keterangan_lokasi')->nullable();
                $table->string('usage')->nullable();
                $table->text('keterangan_usage')->nullable();
                $table->string('keterangan_group_type')->nullable();
                $table->bigInteger('budget')->nullable()->comment('Simpan dalam satuan rupiah, integer');
                $table->string('keterangan_ownership')->nullable();
                $table->string('pengelola')->nullable();
                $table->year('tahun_pengadaan')->nullable();
                $table->year('tahun_perolehan')->nullable();
                $table->string('kondisi')->nullable();
                $table->timestamps();
            });
        } else {
            // Jika tabel sudah ada, kita bisa (opsional) memastikan kolom penting ada.
            Schema::table('data_aset', function (Blueprint $table) {
                // contoh: menambahkan kolom jika belum ada (opsional)
                if (!Schema::hasColumn('data_aset', 'budget')) {
                    $table->bigInteger('budget')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('aset_gereja');
    }
};
