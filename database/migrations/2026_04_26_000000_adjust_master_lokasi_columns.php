<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_lokasi', function (Blueprint $table) {
            // Drop the foreign key and parent_id column
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');

            // Drop columns we don't need anymore
            $table->dropColumn(['gedung', 'lantai', 'ruangan']);

            // Add the new sub_lokasi column
            $table->string('sub_lokasi')->nullable()->after('nama_lokasi');
        });
    }

    public function down(): void
    {
        Schema::table('master_lokasi', function (Blueprint $table) {
            // Revert changes
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->foreign('parent_id')->references('id')->on('master_lokasi')->onDelete('cascade');

            $table->string('gedung')->nullable();
            $table->string('lantai')->nullable();
            $table->string('ruangan')->nullable();

            $table->dropColumn('sub_lokasi');
        });
    }
};
