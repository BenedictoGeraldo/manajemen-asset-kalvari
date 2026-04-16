<?php

namespace App\Providers;

use App\Models\DataAsetKolektif;
use App\Observers\DataAsetKolektifObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

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

        // Deteksi N+1 query di environment local
        // Akan throw exception jika ada relasi yang tidak di-eager load
        if (app()->isLocal()) {
            Model::preventLazyLoading();
        }
    }
}
