<?php

namespace App\Services;

use App\Models\DataAsetKolektif;
use App\Models\TransaksiMutasiAset;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MutasiAsetService
{
    public function getPaginatedMutasi(array $filters = []): LengthAwarePaginator
    {
        $search = $filters['search'] ?? null;
        $status = $filters['status'] ?? null;
        $jenis = $filters['jenis'] ?? null;
        $perPage = $filters['per_page'] ?? 10;

        $query = TransaksiMutasiAset::with(['aset'])
            ->orderByDesc('tanggal_mutasi')
            ->orderByDesc('created_at')
            ->search($search);

        if ($status) {
            $query->where('status', $status);
        }

        if ($jenis) {
            $query->where('jenis_mutasi', $jenis);
        }

        return $query->paginate($perPage);
    }

    public function getById(int $id): TransaksiMutasiAset
    {
        return TransaksiMutasiAset::with([
            'aset.kategori',
            'aset.lokasi',
            'aset.kondisi',
            'aset.pengelola',
            'lokasiLama',
            'lokasiBaru',
            'departmentLama',
            'departmentBaru',
            'penanggungJawabLama',
            'penanggungJawabBaru',
            'kondisi',
            'approver',
            'startedBy',
            'completedBy',
        ])->findOrFail($id);
    }

    public function create(array $data, ?int $userId = null): TransaksiMutasiAset
    {
        return DB::transaction(function () use ($data, $userId) {
            $aset = DataAsetKolektif::findOrFail($data['data_aset_kolektif_id']);

            if (!$aset->is_active) {
                throw new \Exception('Aset tidak aktif dan tidak bisa dimutasi');
            }

            $nomor = $this->generateNomorMutasi($data['tanggal_mutasi']);

            // Snapshot lokasi, department, dan penanggung jawab saat ini
            $data['nomor_mutasi'] = $nomor;
            $data['lokasi_lama_id'] = $aset->lokasi_id;
            // `pengelola_id` berasal dari master_pengelola, bukan tabel departments.
            // Hindari assignment FK yang tidak kompatibel agar proses simpan tidak gagal.
            $data['department_lama_id'] = null;
            $data['penanggung_jawab_lama_id'] = $aset->created_by;
            $data['tanggal_diajukan'] = ($data['status'] ?? 'draft') === 'diajukan' ? now() : null;
            $data['created_by'] = $userId;
            $data['updated_by'] = $userId;

            $mutasi = TransaksiMutasiAset::create($data);

            return $mutasi;
        });
    }

    public function update(int $id, array $data, ?int $userId = null): TransaksiMutasiAset
    {
        return DB::transaction(function () use ($id, $data, $userId) {
            $mutasi = TransaksiMutasiAset::findOrFail($id);

            if (!$mutasi->canEdit()) {
                throw new \Exception('Transaksi mutasi tidak bisa diubah pada status ini');
            }

            $aset = DataAsetKolektif::findOrFail($data['data_aset_kolektif_id']);

            if (!$aset->is_active) {
                throw new \Exception('Aset tidak aktif dan tidak bisa dimutasi');
            }

            $data['updated_by'] = $userId;

            $mutasi->update($data);

            return $mutasi;
        });
    }

    public function delete(int $id, ?int $userId = null): bool
    {
        return DB::transaction(function () use ($id, $userId) {
            $mutasi = TransaksiMutasiAset::findOrFail($id);

            if (!$mutasi->canDelete()) {
                throw new \Exception('Transaksi mutasi tidak bisa dihapus pada status ini');
            }

            $mutasi->deleted_by = $userId;
            $mutasi->save();

            return $mutasi->delete();
        });
    }

    public function approve(int $id, ?int $userId = null, string $catatan = ''): TransaksiMutasiAset
    {
        return DB::transaction(function () use ($id, $userId, $catatan) {
            $mutasi = TransaksiMutasiAset::findOrFail($id);

            if (!in_array($mutasi->status, ['draft', 'diajukan'])) {
                throw new \Exception('Hanya transaksi dengan status draft atau diajukan yang bisa disetujui');
            }

            $mutasi->update([
                'status' => 'disetujui',
                'tanggal_disetujui' => now(),
                'approved_by' => $userId,
                'catatan_approval' => $catatan,
            ]);

            return $mutasi;
        });
    }

    public function reject(int $id, ?int $userId = null, string $catatan = ''): TransaksiMutasiAset
    {
        return DB::transaction(function () use ($id, $userId, $catatan) {
            $mutasi = TransaksiMutasiAset::findOrFail($id);

            if (!in_array($mutasi->status, ['draft', 'diajukan'])) {
                throw new \Exception('Hanya transaksi dengan status draft atau diajukan yang bisa ditolak');
            }

            $mutasi->update([
                'status' => 'ditolak',
                'approved_by' => $userId,
                'catatan_approval' => $catatan,
            ]);

            return $mutasi;
        });
    }

    public function startProcess(int $id, ?int $userId = null): TransaksiMutasiAset
    {
        return DB::transaction(function () use ($id, $userId) {
            $mutasi = TransaksiMutasiAset::findOrFail($id);

            if ($mutasi->status !== 'disetujui') {
                throw new \Exception('Hanya transaksi dengan status disetujui yang bisa dimulai');
            }

            $mutasi->update([
                'status' => 'proses',
                'tanggal_mulai' => now(),
                'started_by' => $userId,
                'tanggal_diajukan' => $mutasi->tanggal_diajukan ?? now(),
            ]);

            return $mutasi;
        });
    }

    public function complete(int $id, array $data, ?int $userId = null): TransaksiMutasiAset
    {
        return DB::transaction(function () use ($id, $data, $userId) {
            $mutasi = TransaksiMutasiAset::findOrFail($id);

            if ($mutasi->status !== 'proses') {
                throw new \Exception('Hanya transaksi dengan status proses yang bisa diselesaikan');
            }

            // Update aset dengan lokasi/department/penanggung jawab baru sesuai jenis mutasi
            $aset = DataAsetKolektif::findOrFail($mutasi->data_aset_kolektif_id);

            if ($mutasi->jenis_mutasi === 'transfer_lokasi' && $mutasi->lokasi_baru_id) {
                $aset->lokasi_id = $mutasi->lokasi_baru_id;
            }

            if ($mutasi->jenis_mutasi === 'perubahan_kondisi' && $mutasi->kondisi_id) {
                $aset->kondisi_id = $mutasi->kondisi_id;
            }

            if ($mutasi->jenis_mutasi === 'write_off' || $mutasi->jenis_mutasi === 'penghapusan') {
                $aset->is_active = false;
            }

            $aset->updated_by = $userId;
            $aset->save();

            $mutasi->update([
                'status' => 'selesai',
                'tanggal_selesai' => now(),
                'completed_by' => $userId,
                'catatan' => $data['catatan'] ?? $mutasi->catatan,
            ]);

            return $mutasi;
        });
    }

    private function generateNomorMutasi(string $tanggalMutasi): string
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            try {
                return DB::transaction(function () use ($tanggalMutasi) {
                    $date = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggalMutasi);
                    $yearMonth = $date->format('Ym');

                    $lastMutasi = TransaksiMutasiAset::withTrashed()
                        ->where('nomor_mutasi', 'like', "MUT-{$yearMonth}-%")
                        ->lockForUpdate()
                        ->latest('nomor_mutasi')
                        ->first();

                    $nextNumber = 1;
                    if ($lastMutasi) {
                        $parts = explode('-', $lastMutasi->nomor_mutasi);
                        $nextNumber = (int) $parts[2] + 1;
                    }

                    $nomor = 'MUT-' . $yearMonth . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                    return $nomor;
                });
            } catch (QueryException $e) {
                if (!$this->isDuplicateNomorMutasiException($e)) {
                    throw $e;
                }
            }
        }

        throw new \Exception('Gagal membuat nomor mutasi unik setelah 5 percobaan');
    }

    private function isDuplicateNomorMutasiException(QueryException $e): bool
    {
        return $e->getCode() === '23000' && str_contains($e->getMessage(), '1062');
    }
}
