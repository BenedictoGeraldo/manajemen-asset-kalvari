<?php

namespace App\Services;

use App\Models\DataAsetKolektif;
use App\Models\TransaksiPemeliharaan;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PemeliharaanService
{
    public function getPaginatedPemeliharaan(array $filters = []): LengthAwarePaginator
    {
        $search = $filters['search'] ?? null;
        $status = $filters['status'] ?? null;
        $jenis = $filters['jenis'] ?? null;
        $perPage = $filters['per_page'] ?? 10;

        $query = TransaksiPemeliharaan::with(['aset'])
            ->orderByDesc('tanggal_pengajuan')
            ->orderByDesc('created_at')
            ->search($search);

        if ($status) {
            $query->where('status', $status);
        }

        if ($jenis) {
            $query->where('jenis_pemeliharaan', $jenis);
        }

        return $query->paginate($perPage);
    }

    public function getById(int $id): TransaksiPemeliharaan
    {
        return TransaksiPemeliharaan::with([
            'aset.kategori',
            'aset.lokasi',
            'aset.kondisi',
            'kondisiSebelum',
            'kondisiSesudah',
            'approver',
            'startedBy',
            'completedBy',
        ])->findOrFail($id);
    }

    public function create(array $data, ?int $userId = null): TransaksiPemeliharaan
    {
        $maxAttempts = 5;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                return DB::transaction(function () use ($data, $userId) {
                    $aset = DataAsetKolektif::findOrFail((int) $data['data_aset_kolektif_id']);

                    if (!$aset->is_active) {
                        throw new \RuntimeException('Aset tidak aktif dan tidak dapat diajukan untuk pemeliharaan.');
                    }

                    $data['kondisi_sebelum_id'] = $data['kondisi_sebelum_id'] ?? $aset->kondisi_id;
                    $data['estimasi_biaya'] = $data['estimasi_biaya'] ?? 0;
                    $data['realisasi_biaya'] = $data['realisasi_biaya'] ?? 0;

                    return TransaksiPemeliharaan::create([
                        ...$data,
                        'nomor_pemeliharaan' => $this->generateNomorPemeliharaan($data['tanggal_pengajuan']),
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ]);
                });
            } catch (QueryException $e) {
                if (!$this->isDuplicateNomorPemeliharaanException($e) || $attempt === $maxAttempts) {
                    throw $e;
                }
            }
        }

        throw new \RuntimeException('Gagal membuat transaksi pemeliharaan karena bentrok nomor.');
    }

    public function update(int $id, array $data, ?int $userId = null): TransaksiPemeliharaan
    {
        return DB::transaction(function () use ($id, $data, $userId) {
            $pemeliharaan = TransaksiPemeliharaan::findOrFail($id);

            if (!$pemeliharaan->canEdit()) {
                throw new \RuntimeException('Transaksi pada status saat ini tidak dapat diubah.');
            }

            $aset = DataAsetKolektif::findOrFail((int) $data['data_aset_kolektif_id']);
            if (!$aset->is_active) {
                throw new \RuntimeException('Aset tidak aktif dan tidak dapat diajukan untuk pemeliharaan.');
            }

            $data['kondisi_sebelum_id'] = $data['kondisi_sebelum_id'] ?? $aset->kondisi_id;
            $data['estimasi_biaya'] = $data['estimasi_biaya'] ?? 0;
            $data['realisasi_biaya'] = $data['realisasi_biaya'] ?? 0;

            $pemeliharaan->update([
                ...$data,
                'updated_by' => $userId,
            ]);

            return $pemeliharaan;
        });
    }

    public function delete(int $id, ?int $userId = null): bool
    {
        return DB::transaction(function () use ($id, $userId) {
            $pemeliharaan = TransaksiPemeliharaan::findOrFail($id);

            if (!$pemeliharaan->canDelete()) {
                throw new \RuntimeException('Transaksi pada status saat ini tidak dapat dihapus.');
            }

            $pemeliharaan->update(['deleted_by' => $userId]);

            return (bool) $pemeliharaan->delete();
        });
    }

    public function approve(int $id, int $userId, ?string $catatan = null): TransaksiPemeliharaan
    {
        return DB::transaction(function () use ($id, $userId, $catatan) {
            $pemeliharaan = TransaksiPemeliharaan::findOrFail($id);

            if (!in_array($pemeliharaan->status, ['draft', 'diajukan'], true)) {
                throw new \RuntimeException('Status transaksi tidak dapat disetujui.');
            }

            $pemeliharaan->update([
                'status' => 'disetujui',
                'tanggal_disetujui' => now(),
                'approved_by' => $userId,
                'catatan_approval' => $catatan,
                'updated_by' => $userId,
            ]);

            return $pemeliharaan;
        });
    }

    public function reject(int $id, int $userId, ?string $catatan = null): TransaksiPemeliharaan
    {
        return DB::transaction(function () use ($id, $userId, $catatan) {
            $pemeliharaan = TransaksiPemeliharaan::findOrFail($id);

            if (!in_array($pemeliharaan->status, ['draft', 'diajukan'], true)) {
                throw new \RuntimeException('Status transaksi tidak dapat ditolak.');
            }

            $pemeliharaan->update([
                'status' => 'ditolak',
                'catatan_approval' => $catatan,
                'updated_by' => $userId,
            ]);

            return $pemeliharaan;
        });
    }

    public function startProcess(int $id, int $userId): TransaksiPemeliharaan
    {
        return DB::transaction(function () use ($id, $userId) {
            $pemeliharaan = TransaksiPemeliharaan::findOrFail($id);

            if ($pemeliharaan->status !== 'disetujui') {
                throw new \RuntimeException('Pemeliharaan hanya dapat dimulai dari status disetujui.');
            }

            $pemeliharaan->update([
                'status' => 'proses',
                'tanggal_mulai' => now(),
                'started_by' => $userId,
                'updated_by' => $userId,
            ]);

            return $pemeliharaan;
        });
    }

    public function complete(int $id, array $data, int $userId): TransaksiPemeliharaan
    {
        return DB::transaction(function () use ($id, $data, $userId) {
            $pemeliharaan = TransaksiPemeliharaan::with('aset')->findOrFail($id);

            if ($pemeliharaan->status !== 'proses') {
                throw new \RuntimeException('Pemeliharaan hanya dapat diselesaikan dari status proses.');
            }

            $pemeliharaan->update([
                'status' => 'selesai',
                'tanggal_selesai' => $data['tanggal_selesai'],
                'tindakan' => $data['tindakan'],
                'realisasi_biaya' => $data['realisasi_biaya'],
                'kondisi_sesudah_id' => $data['kondisi_sesudah_id'],
                'catatan' => $data['catatan'] ?? $pemeliharaan->catatan,
                'completed_by' => $userId,
                'updated_by' => $userId,
            ]);

            $pemeliharaan->aset()->update([
                'kondisi_id' => $data['kondisi_sesudah_id'],
            ]);

            return $pemeliharaan;
        });
    }

    private function generateNomorPemeliharaan(string $tanggal): string
    {
        $date = date_create($tanggal);
        $prefix = 'PMH-' . date_format($date, 'Ym');

        $maxSequence = (int) TransaksiPemeliharaan::withTrashed()
            ->where('nomor_pemeliharaan', 'like', $prefix . '-%')
            ->lockForUpdate()
            ->selectRaw('COALESCE(MAX(CAST(RIGHT(nomor_pemeliharaan, 4) AS UNSIGNED)), 0) as max_sequence')
            ->value('max_sequence');

        return sprintf('%s-%04d', $prefix, $maxSequence + 1);
    }

    private function isDuplicateNomorPemeliharaanException(QueryException $e): bool
    {
        $sqlState = $e->errorInfo[0] ?? null;
        $driverCode = (int) ($e->errorInfo[1] ?? 0);

        return $sqlState === '23000'
            && $driverCode === 1062
            && str_contains($e->getMessage(), 'nomor_pemeliharaan');
    }
}
