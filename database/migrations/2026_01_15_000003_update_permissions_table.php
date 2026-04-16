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
        Schema::table('permissions', function (Blueprint $table) {
            // Add slug column after name
            $table->string('slug')->unique()->nullable()->after('name');

            // Add soft deletes
            $table->softDeletes()->after('updated_at');
        });

        // Update existing permissions to have slug
        DB::table('permissions')->get()->each(function ($permission) {
            DB::table('permissions')
                ->where('id', $permission->id)
                ->update(['slug' => \Illuminate\Support\Str::slug($permission->name)]);
        });

        // Make slug non-nullable after populating
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn(['slug', 'deleted_at']);
        });
    }
};
