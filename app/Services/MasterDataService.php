<?php

namespace App\Services;

use App\Models\MasterKategori;
use App\Models\MasterLokasi;
use App\Models\MasterKondisi;
use App\Models\MasterPengelola;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class MasterDataService
{
    /**
     * Get active categories with caching
     *
     * @return Collection
     */
    public function getActiveKategoris(): Collection
    {
        return Cache::remember('active_kategoris', 3600, function () {
            return MasterKategori::active()->orderBy('nama_kategori')->get();
        });
    }

    /**
     * Get active locations with caching
     *
     * @return Collection
     */
    public function getActiveLokasis(): Collection
    {
        return Cache::remember('active_lokasis', 3600, function () {
            return MasterLokasi::active()->orderBy('gedung')->orderBy('lantai')->get();
        });
    }

    /**
     * Get active conditions with caching
     *
     * @return Collection
     */
    public function getActiveKondisis(): Collection
    {
        return Cache::remember('active_kondisis', 3600, function () {
            return MasterKondisi::active()->ordered()->get();
        });
    }

    /**
     * Get active administrators with caching
     *
     * @return Collection
     */
    public function getActivePengelolas(): Collection
    {
        return Cache::remember('active_pengelolas', 3600, function () {
            return MasterPengelola::active()->orderBy('nama_pengelola')->get();
        });
    }

    /**
     * Clear all master data cache
     *
     * @return void
     */
    public function clearMasterDataCache(): void
    {
        Cache::forget('active_kategoris');
        Cache::forget('active_lokasis');
        Cache::forget('active_kondisis');
        Cache::forget('active_pengelolas');
    }
}
