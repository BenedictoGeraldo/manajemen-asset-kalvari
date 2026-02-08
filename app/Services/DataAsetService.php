<?php

namespace App\Services;

use App\Models\DataAsetKolektif;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class DataAsetService
{
    /**
     * Get paginated assets with search functionality
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getPaginatedAsets(array $filters = []): LengthAwarePaginator
    {
        $search = $filters['search'] ?? null;
        $perPage = $filters['per_page'] ?? 10;

        $query = DataAsetKolektif::with(['kategori', 'lokasi', 'kondisi', 'pengelola'])
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_aset', 'like', "%$search%")
                  ->orWhere('kode_aset', 'like', "%$search%")
                  ->orWhereHas('kategori', function($k) use ($search) {
                      $k->where('nama_kategori', 'like', "%$search%");
                  })
                  ->orWhereHas('lokasi', function($l) use ($search) {
                      $l->where('nama_lokasi', 'like', "%$search%");
                  })
                  ->orWhereHas('pengelola', function($p) use ($search) {
                      $p->where('nama_pengelola', 'like', "%$search%");
                  });
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Get asset by ID with relationships
     *
     * @param int $id
     * @return DataAsetKolektif
     */
    public function getAsetById(int $id): DataAsetKolektif
    {
        return DataAsetKolektif::with(['kategori', 'lokasi', 'kondisi', 'pengelola'])
            ->findOrFail($id);
    }

    /**
     * Create a new asset
     *
     * @param array $data
     * @return DataAsetKolektif
     */
    public function createAset(array $data): DataAsetKolektif
    {
        DB::beginTransaction();
        try {
            $aset = DataAsetKolektif::create($data);

            // Clear cache after creating
            $this->clearRelatedCache();

            DB::commit();
            return $aset;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update an existing asset
     *
     * @param int $id
     * @param array $data
     * @return DataAsetKolektif
     */
    public function updateAset(int $id, array $data): DataAsetKolektif
    {
        DB::beginTransaction();
        try {
            $aset = DataAsetKolektif::findOrFail($id);
            $aset->update($data);

            // Clear cache after updating
            $this->clearRelatedCache();

            DB::commit();
            return $aset;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete an asset
     *
     * @param int $id
     * @return bool
     */
    public function deleteAset(int $id): bool
    {
        DB::beginTransaction();
        try {
            $aset = DataAsetKolektif::findOrFail($id);
            $result = $aset->delete();

            // Clear cache after deleting
            $this->clearRelatedCache();

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get dashboard statistics
     *
     * @return array
     */
    public function getDashboardStats(): array
    {
        return Cache::remember('dashboard_stats', 3600, function () {
            return [
                'total_aset' => DataAsetKolektif::sum('jumlah_barang'),
                'total_nilai' => DataAsetKolektif::sum('nilai_pengadaan_total'),
                'total_record' => DataAsetKolektif::count(),
                'distribusi_kondisi' => $this->getDistribusiKondisi(),
                'distribusi_kategori' => $this->getDistribusiKategori(),
            ];
        });
    }

    /**
     * Get asset distribution by condition
     *
     * @return \Illuminate\Support\Collection
     */
    private function getDistribusiKondisi()
    {
        return DataAsetKolektif::select('kondisi_id', DB::raw('count(*) as total'))
            ->with('kondisi')
            ->groupBy('kondisi_id')
            ->get();
    }

    /**
     * Get asset distribution by category (top 5)
     *
     * @return \Illuminate\Support\Collection
     */
    private function getDistribusiKategori()
    {
        return DataAsetKolektif::select('kategori_id', DB::raw('count(*) as total'))
            ->with('kategori')
            ->groupBy('kategori_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();
    }

    /**
     * Clear related cache
     *
     * @return void
     */
    private function clearRelatedCache(): void
    {
        Cache::forget('dashboard_stats');
        Cache::forget('active_kategoris');
        Cache::forget('active_lokasis');
        Cache::forget('active_kondisis');
        Cache::forget('active_pengelolas');
    }
}
