<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_pembelian', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable()->after('catatan');
            $table->text('alasan_penolakan')->nullable()->after('department_id');
            $table->timestamp('rejected_at')->nullable()->after('approved_by');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');

            $table->index('department_id');

            $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
            $table->foreign('rejected_by')->references('id')->on('users')->nullOnDelete();
        });

        DB::statement("ALTER TABLE transaksi_pembelian MODIFY COLUMN status ENUM('draft','diajukan','disetujui','ditolak','dibatalkan') DEFAULT 'draft'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transaksi_pembelian MODIFY COLUMN status ENUM('draft','diajukan','disetujui','dibatalkan') DEFAULT 'draft'");

        Schema::table('transaksi_pembelian', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn(['department_id', 'alasan_penolakan', 'rejected_at', 'rejected_by']);
        });
    }
};
