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
        Schema::table('master_pengelola', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('kode_pengelola')->constrained('departments')->onDelete('set null');
            $table->dropColumn('departemen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_pengelola', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
            $table->string('departemen')->nullable()->after('jabatan');
        });
    }
};
