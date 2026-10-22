<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_aset_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_aset_kolektif_id')->constrained('data_aset_kolektif')->cascadeOnDelete();
            $table->string('kode_unit', 50)->unique();
            $table->unsignedInteger('nomor_urut');
            $table->foreignId('kondisi_id')->nullable()->constrained('master_kondisi')->nullOnDelete();
            $table->string('status', 30)->default('tersedia'); // tersedia, dipinjam, dalam_perbaikan, dimusnahkan, hilang
            $table->longText('gambar_unit_base64')->nullable();
            $table->text('catatan_khusus')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['data_aset_kolektif_id', 'nomor_urut']);
            $table->index('status');
        });

        // Backfill units for all existing collective assets
        $asets = DB::table('data_aset_kolektif')->select('id', 'kode_aset', 'jumlah_barang', 'kondisi_id', 'created_at', 'updated_at')->get();
        $now = now();

        foreach ($asets as $aset) {
            $count = max(1, (int) $aset->jumlah_barang);
            $prefix = $aset->kode_aset ?? ('AST-' . $aset->id);
            $unitsToInsert = [];

            for ($i = 1; $i <= $count; $i++) {
                $unitsToInsert[] = [
                    'data_aset_kolektif_id' => $aset->id,
                    'kode_unit' => $prefix . '-' . sprintf('%03d', $i),
                    'nomor_urut' => $i,
                    'kondisi_id' => $aset->kondisi_id,
                    'status' => 'tersedia',
                    'gambar_unit_base64' => null,
                    'catatan_khusus' => null,
                    'created_by' => null,
                    'updated_by' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (!empty($unitsToInsert)) {
                foreach (array_chunk($unitsToInsert, 100) as $chunk) {
                    DB::table('data_aset_units')->insert($chunk);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_aset_units');
    }
};
