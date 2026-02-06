<?php

namespace App\Observers;

use App\Models\DataAsetKolektif;

class DataAsetKolektifObserver
{
    /**
     * Handle the DataAsetKolektif "creating" event.
     *
     * @param  \App\Models\DataAsetKolektif  $dataAsetKolektif
     * @return void
     */
    public function creating(DataAsetKolektif $dataAsetKolektif): void
    {
        if (empty($dataAsetKolektif->kode_aset)) {
            $dataAsetKolektif->kode_aset = $this->generateKodeAset();
        }
    }

    /**
     * Generate unique asset code
     *
     * @return string
     */
    private function generateKodeAset(): string
    {
        $tahun = date('Y');
        $count = DataAsetKolektif::withTrashed()
            ->whereYear('created_at', $tahun)
            ->count() + 1;

        return sprintf('AST-%s-%05d', $tahun, $count);
    }
}
