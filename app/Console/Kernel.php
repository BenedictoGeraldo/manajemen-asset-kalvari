<?php

namespace App\Console;

use App\Console\Commands\SyncOverduePeminjaman;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Sync status peminjaman terlambat setiap hari pukul 00:05
        // Ini menggantikan syncOverdueStatuses() yang sebelumnya dipanggil di setiap request
        $schedule->command(SyncOverduePeminjaman::class)->dailyAt('00:05');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
