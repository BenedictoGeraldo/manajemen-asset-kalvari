    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Menjalankan migration.
         */
        public function up(): void
        {
            Schema::table('data_aset', function (Blueprint $table) {
                $table->id()->first();
            });
        }

        /**
         * Membatalkan migration.
         */
        public function down(): void
        {
            Schema::table('data_aset', function (Blueprint $table) {
                // Jika migration di-rollback, hapus kolom 'id'
                $table->dropColumn('id');
            });
        }
    };