<?php

namespace App\Console\Commands;

use App\Enums\PeminjamanStatus;
use App\Models\TransaksiPeminjaman;
use Illuminate\Console\Command;

class SyncOverduePeminjaman extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'peminjaman:sync-overdue';

    /**
     * The console command description.
     */
    protected $description = 'Mengubah status peminjaman yang sudah melewati tanggal kembali menjadi TERLAMBAT';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $updated = TransaksiPeminjaman::query()
            ->where('status', PeminjamanStatus::DIPINJAM)
            ->whereNotNull('tanggal_rencana_kembali')
            ->whereDate('tanggal_rencana_kembali', '<', now()->toDateString())
            ->update(['status' => PeminjamanStatus::TERLAMBAT]);

        $this->info("Sync selesai: {$updated} transaksi diperbarui ke status TERLAMBAT.");
    }
}
