<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\DataAsetKolektif;
use App\Observers\DataAsetKolektifObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Observer for DataAsetKolektif
        DataAsetKolektif::observe(DataAsetKolektifObserver::class);
    }
}
