<?php

namespace App\Services;

use App\Models\DataAsetKolektif;
use App\Models\TransaksiPeminjaman;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PeminjamanService
{
    public function getPaginatedPeminjaman(array $filters = []): LengthAwarePaginator
    {
        $this->syncOverdueStatuses();

        $search = $filters['search'] ?? null;
        $status = $filters['status'] ?? null;
        $perPage = $filters['per_page'] ?? 10;

        $query = TransaksiPeminjaman::withCount('items')
            ->orderByDesc('tanggal_pengajuan')
            ->orderByDesc('created_at')
            ->search($search);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }

    public function getById(int $id): TransaksiPeminjaman
    {
        $this->syncOverdueStatuses();

        return TransaksiPeminjaman::with([
            'items.aset.kategori',
            'items.aset.lokasi',
            'items.kondisiAwal',
            'items.kondisiAkhir',
            'approver',
            'handoverBy',
            'returnedBy',
        ])->findOrFail($id);
    }

    public function create(array $data, ?int $userId = null): TransaksiPeminjaman
    {
        $maxAttempts = 5;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                return DB::transaction(function () use ($data, $userId) {
                    $items = $this->normalizeItems($data['items']);
                    unset($data['items']);

                    $peminjaman = TransaksiPeminjaman::create([
                        ...$data,
                        'nomor_peminjaman' => $this->generateNomorPeminjaman($data['tanggal_pengajuan']),
                        'created_by' => $userId,
                        'updated_by' => $userId,
                    ]);

                    foreach ($items as $item) {
                        $aset = DataAsetKolektif::findOrFail($item['data_aset_kolektif_id']);

                        $peminjaman->items()->create([
                            ...$item,
                            'aset_kode_snapshot' => $aset->kode_aset,
                            'aset_nama_snapshot' => $aset->nama_aset,
                        ]);
                    }

                    return $peminjaman->load('items');
                });
            } catch (QueryException $e) {
                if (!$this->isDuplicateNomorPeminjamanException($e) || $attempt === $maxAttempts) {
                    throw $e;
                }
            }
        }

        throw new \RuntimeException('Gagal membuat transaksi peminjaman karena bentrok nomor peminjaman.');
    }

    public function update(int $id, array $data, ?int $userId = null): TransaksiPeminjaman
    {
        return DB::transaction(function () use ($id, $data, $userId) {
            $peminjaman = TransaksiPeminjaman::with('items')->findOrFail($id);

            if (!$peminjaman->canEdit()) {
                throw new \RuntimeException('Transaksi pada status saat ini tidak dapat diubah.');
            }

            $items = $this->normalizeItems($data['items']);
            unset($data['items']);

            $peminjaman->update([
                ...$data,
                'updated_by' => $userId,
            ]);

            $peminjaman->items()->delete();
            foreach ($items as $item) {
                $aset = DataAsetKolektif::findOrFail($item['data_aset_kolektif_id']);

                $peminjaman->items()->create([
                    ...$item,
                    'aset_kode_snapshot' => $aset->kode_aset,
                    'aset_nama_snapshot' => $aset->nama_aset,
                ]);
            }

            return $peminjaman->load('items');
        });
    }

    public function delete(int $id, ?int $userId = null): bool
    {
        return DB::transaction(function () use ($id, $userId) {
            $peminjaman = TransaksiPeminjaman::findOrFail($id);

            if (!$peminjaman->canDelete()) {
                throw new \RuntimeException('Transaksi pada status saat ini tidak dapat dihapus.');
            }

            $peminjaman->update(['deleted_by' => $userId]);

            return (bool) $peminjaman->delete();
        });
    }

    public function approve(int $id, int $userId, ?string $catatan = null): TransaksiPeminjaman
    {
        return DB::transaction(function () use ($id, $userId, $catatan) {
            $peminjaman = TransaksiPeminjaman::findOrFail($id);

            if (!in_array($peminjaman->status, ['draft', 'diajukan'], true)) {
                throw new \RuntimeException('Status transaksi tidak dapat disetujui.');
            }

            $peminjaman->update([
                'status' => 'disetujui',
                'tanggal_disetujui' => now(),
                'approved_by' => $userId,
                'catatan_approval' => $catatan,
                'updated_by' => $userId,
            ]);

            return $peminjaman;
        });
    }

    public function reject(int $id, int $userId, ?string $catatan = null): TransaksiPeminjaman
    {
        return DB::transaction(function () use ($id, $userId, $catatan) {
            $peminjaman = TransaksiPeminjaman::findOrFail($id);

            if (!in_array($peminjaman->status, ['draft', 'diajukan'], true)) {
                throw new \RuntimeException('Status transaksi tidak dapat ditolak.');
            }

            $peminjaman->update([
                'status' => 'ditolak',
                'catatan_approval' => $catatan,
                'updated_by' => $userId,
            ]);

            return $peminjaman;
        });
    }

    public function handover(int $id, array $data, int $userId): TransaksiPeminjaman
    {
        return DB::transaction(function () use ($id, $data, $userId) {
            $peminjaman = TransaksiPeminjaman::with('items.aset')->findOrFail($id);

            if ($peminjaman->status !== 'disetujui') {
                throw new \RuntimeException('Serah terima hanya dapat dilakukan pada transaksi disetujui.');
            }

            $itemsPayload = collect($data['items'] ?? [])->keyBy(fn ($item) => (int) $item['id']);

            foreach ($peminjaman->items as $item) {
                $payload = $itemsPayload->get($item->id, []);

                $aset = DataAsetKolektif::where('id', $item->data_aset_kolektif_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($aset->jumlah_barang < $item->jumlah) {
                    throw new \RuntimeException('Stok aset "' . $aset->nama_aset . '" tidak mencukupi untuk dipinjam.');
                }

                $aset->decrement('jumlah_barang', $item->jumlah);

                $item->update([
                    'kondisi_awal_id' => $payload['kondisi_awal_id'] ?? null,
                    'catatan_serah_terima' => $payload['catatan_serah_terima'] ?? null,
                ]);
            }

            $peminjaman->update([
                'status' => 'dipinjam',
                'tanggal_serah_terima' => $data['tanggal_serah_terima'] ?? now(),
                'handover_by' => $userId,
                'catatan_serah_terima' => $data['catatan_serah_terima'] ?? null,
                'updated_by' => $userId,
            ]);

            return $peminjaman->load('items.aset');
        });
    }

    public function returnAssets(int $id, array $data, int $userId): TransaksiPeminjaman
    {
        return DB::transaction(function () use ($id, $data, $userId) {
            $peminjaman = TransaksiPeminjaman::with('items.aset')->findOrFail($id);

            if (!in_array($peminjaman->status, ['dipinjam', 'terlambat'], true)) {
                throw new \RuntimeException('Pengembalian hanya dapat dilakukan pada transaksi dipinjam/terlambat.');
            }

            $itemsPayload = collect($data['items'] ?? [])->keyBy(fn ($item) => (int) $item['id']);

            foreach ($peminjaman->items as $item) {
                $payload = $itemsPayload->get($item->id, []);

                $aset = DataAsetKolektif::where('id', $item->data_aset_kolektif_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $aset->increment('jumlah_barang', $item->jumlah);

                $item->update([
                    'kondisi_akhir_id' => $payload['kondisi_akhir_id'] ?? null,
                    'catatan_pengembalian' => $payload['catatan_pengembalian'] ?? null,
                    'returned_at' => $data['tanggal_dikembalikan'] ?? now(),
                ]);
            }

            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_dikembalikan' => $data['tanggal_dikembalikan'] ?? now(),
                'returned_by' => $userId,
                'catatan_pengembalian' => $data['catatan_pengembalian'] ?? null,
                'updated_by' => $userId,
            ]);

            return $peminjaman->load('items.aset');
        });
    }

    private function normalizeItems(array $items): array
    {
        $normalized = collect($items)
            ->filter(fn ($item) => !empty($item['data_aset_kolektif_id']) && (int) ($item['jumlah'] ?? 0) > 0)
            ->groupBy(fn ($item) => (int) $item['data_aset_kolektif_id'])
            ->map(function ($group) {
                $first = $group->first();

                return [
                    'data_aset_kolektif_id' => (int) $first['data_aset_kolektif_id'],
                    'jumlah' => (int) $group->sum(fn ($row) => (int) $row['jumlah']),
                    'catatan_item' => $first['catatan_item'] ?? null,
                ];
            })
            ->values()
            ->all();

        if (empty($normalized)) {
            throw new \RuntimeException('Minimal satu item aset harus diisi.');
        }

        return $normalized;
    }

    private function syncOverdueStatuses(): void
    {
        TransaksiPeminjaman::query()
            ->where('status', 'dipinjam')
            ->whereNotNull('tanggal_rencana_kembali')
            ->whereDate('tanggal_rencana_kembali', '<', now()->toDateString())
            ->update(['status' => 'terlambat']);
    }

    private function generateNomorPeminjaman(string $tanggal): string
    {
        $date = date_create($tanggal);
        $prefix = 'PMJ-' . date_format($date, 'Ym');

        $maxSequence = (int) TransaksiPeminjaman::withTrashed()
            ->where('nomor_peminjaman', 'like', $prefix . '-%')
            ->lockForUpdate()
            ->selectRaw('COALESCE(MAX(CAST(RIGHT(nomor_peminjaman, 4) AS UNSIGNED)), 0) as max_sequence')
            ->value('max_sequence');

        return sprintf('%s-%04d', $prefix, $maxSequence + 1);
    }

    private function isDuplicateNomorPeminjamanException(QueryException $e): bool
    {
        $sqlState = $e->errorInfo[0] ?? null;
        $driverCode = (int) ($e->errorInfo[1] ?? 0);

        return $sqlState === '23000'
            && $driverCode === 1062
            && str_contains($e->getMessage(), 'nomor_peminjaman');
    }
}
