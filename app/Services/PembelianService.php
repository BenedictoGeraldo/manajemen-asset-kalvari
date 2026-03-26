<?php

namespace App\Services;

use App\Models\DataAsetKolektif;
use App\Models\TransaksiPembelian;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PembelianService
{
    public function getPaginatedPembelian(array $filters = []): LengthAwarePaginator
    {
        $search = $filters['search'] ?? null;
        $status = $filters['status'] ?? null;
        $perPage = $filters['per_page'] ?? 10;

        $query = TransaksiPembelian::withCount('items')
            ->orderBy('tanggal_pembelian', 'desc')
            ->orderBy('created_at', 'desc')
            ->search($search);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }

    public function getById(int $id): TransaksiPembelian
    {
        return TransaksiPembelian::with([
            'items.kategori',
            'items.lokasi',
            'items.kondisi',
            'items.pengelola',
            'items.asetKolektif',
            'approver',
        ])->findOrFail($id);
    }

    public function create(array $data, ?int $userId = null): TransaksiPembelian
    {
        return DB::transaction(function () use ($data, $userId) {
            $items = $data['items'];
            unset($data['items']);

            $totalNilai = $this->calculateTotal($items);

            $pembelian = TransaksiPembelian::create([
                ...$data,
                'nomor_pembelian' => $this->generateNomorPembelian($data['tanggal_pembelian']),
                'total_nilai' => $totalNilai,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            foreach ($items as $item) {
                $pembelian->items()->create([
                    ...$item,
                    'subtotal' => (float) $item['jumlah'] * (float) $item['harga_satuan'],
                ]);
            }

            return $pembelian->load('items');
        });
    }

    public function update(int $id, array $data, ?int $userId = null): TransaksiPembelian
    {
        return DB::transaction(function () use ($id, $data, $userId) {
            $pembelian = TransaksiPembelian::with('items')->findOrFail($id);

            if ($pembelian->status === 'disetujui') {
                throw new \RuntimeException('Transaksi yang sudah disetujui tidak dapat diubah.');
            }

            $items = $data['items'];
            unset($data['items']);

            $totalNilai = $this->calculateTotal($items);

            $pembelian->update([
                ...$data,
                'total_nilai' => $totalNilai,
                'updated_by' => $userId,
            ]);

            $pembelian->items()->delete();
            foreach ($items as $item) {
                $pembelian->items()->create([
                    ...$item,
                    'subtotal' => (float) $item['jumlah'] * (float) $item['harga_satuan'],
                ]);
            }

            return $pembelian->load('items');
        });
    }

    public function delete(int $id, ?int $userId = null): bool
    {
        return DB::transaction(function () use ($id, $userId) {
            $pembelian = TransaksiPembelian::findOrFail($id);

            if ($pembelian->status === 'disetujui') {
                throw new \RuntimeException('Transaksi yang sudah disetujui tidak dapat dihapus.');
            }

            $pembelian->update(['deleted_by' => $userId]);
            return (bool) $pembelian->delete();
        });
    }

    public function approveAndPostToAset(int $id, int $userId): TransaksiPembelian
    {
        return DB::transaction(function () use ($id, $userId) {
            $pembelian = TransaksiPembelian::with('items')->findOrFail($id);

            if ($pembelian->status === 'disetujui' && $pembelian->is_posted_to_aset) {
                return $pembelian->load('items.asetKolektif');
            }

            foreach ($pembelian->items as $item) {
                if ($item->aset_kolektif_id) {
                    continue;
                }

                $aset = DataAsetKolektif::create([
                    'nama_aset' => $item->nama_item,
                    'kategori_id' => $item->kategori_id,
                    'deskripsi_aset' => $item->deskripsi,
                    'ukuran' => null,
                    'deskripsi_ukuran_bentuk' => null,
                    'lokasi_id' => $item->lokasi_id,
                    'kegunaan' => $item->kegunaan,
                    'keterangan_kegunaan' => 'Dibuat dari transaksi pembelian ' . $pembelian->nomor_pembelian,
                    'jumlah_barang' => $item->jumlah,
                    'tipe_grup' => $item->jumlah > 1 ? 'set' : 'individual',
                    'keterangan_tipe_grup' => null,
                    'budget' => $item->subtotal,
                    'keterangan_budget' => $pembelian->sumber_dana,
                    'pengelola_id' => $item->pengelola_id,
                    'tahun_pengadaan' => $item->tahun_pengadaan,
                    'nilai_pengadaan_total' => $item->subtotal,
                    'nilai_pengadaan_per_unit' => $item->harga_satuan,
                    'kondisi_id' => $item->kondisi_id,
                    'catatan' => $item->catatan,
                    'is_active' => true,
                ]);

                $item->update(['aset_kolektif_id' => $aset->id]);
            }

            $pembelian->update([
                'status' => 'disetujui',
                'is_posted_to_aset' => true,
                'approved_at' => now(),
                'approved_by' => $userId,
                'updated_by' => $userId,
            ]);

            return $pembelian->load('items.asetKolektif');
        });
    }

    private function calculateTotal(array $items): float
    {
        return collect($items)->sum(function ($item) {
            return (float) $item['jumlah'] * (float) $item['harga_satuan'];
        });
    }

    private function generateNomorPembelian(string $tanggal): string
    {
        $date = date_create($tanggal);
        $prefix = 'PB-' . date_format($date, 'Ym');

        $last = TransaksiPembelian::where('nomor_pembelian', 'like', $prefix . '-%')
            ->orderByDesc('id')
            ->first();

        $sequence = 1;
        if ($last) {
            $parts = explode('-', $last->nomor_pembelian);
            $sequence = ((int) end($parts)) + 1;
        }

        return sprintf('%s-%04d', $prefix, $sequence);
    }
}
