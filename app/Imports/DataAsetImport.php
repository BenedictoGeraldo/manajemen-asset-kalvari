<?php

namespace App\Imports;

use App\Models\DataAsetKolektif;
use App\Services\DataAsetService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DataAsetImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected int $imported = 0;
    protected int $skipped = 0;
    protected array $errors = [];

    public function collection(Collection $rows)
    {
        $service = app(DataAsetService::class);

        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;

                if (empty($row['nama_aset'])) {
                    $this->skipped++;
                    continue;
                }

                $kategoriId = $this->resolveForeignKey('master_kategori', 'nama_kategori', $row['kategori'] ?? null);
                if (!$kategoriId) {
                    $this->errors[] = "Baris {$rowNumber}: Kategori '{$row['kategori']}' tidak ditemukan.";
                    $this->skipped++;
                    continue;
                }

                $lokasiId = $this->resolveForeignKey('master_lokasi', 'nama_lokasi', $row['lokasi'] ?? null);
                if (!$lokasiId) {
                    $this->errors[] = "Baris {$rowNumber}: Lokasi '{$row['lokasi']}' tidak ditemukan.";
                    $this->skipped++;
                    continue;
                }

                $kondisiId = $this->resolveForeignKey('master_kondisi', 'nama_kondisi', $row['kondisi'] ?? null);
                if (!$kondisiId) {
                    $this->errors[] = "Baris {$rowNumber}: Kondisi '{$row['kondisi']}' tidak ditemukan.";
                    $this->skipped++;
                    continue;
                }

                $pengelolaId = $this->resolveForeignKey('master_pengelola', 'nama_pengelola', $row['pengelola'] ?? null);
                if (!$pengelolaId) {
                    $this->errors[] = "Baris {$rowNumber}: Pengelola '{$row['pengelola']}' tidak ditemukan.";
                    $this->skipped++;
                    continue;
                }

                $departmentId = null;
                if (!empty($row['departemen'])) {
                    $departmentId = $this->resolveForeignKey('departments', 'name', $row['departemen']);
                }

                $data = [
                    'nama_aset' => $row['nama_aset'],
                    'kategori_id' => $kategoriId,
                    'deskripsi_aset' => $row['deskripsi_aset'] ?? null,
                    'ukuran' => $row['ukuran'] ?? null,
                    'deskripsi_ukuran_bentuk' => $row['deskripsi_ukuran_bentuk'] ?? null,
                    'lokasi_id' => $lokasiId,
                    'kegunaan' => $row['kegunaan'] ?? null,
                    'keterangan_kegunaan' => $row['keterangan_kegunaan'] ?? null,
                    'jumlah_barang' => (int) ($row['jumlah_barang'] ?? 1),
                    'tipe_grup' => $row['tipe_grup'] ?? 'individual',
                    'keterangan_tipe_grup' => $row['keterangan_tipe_grup'] ?? null,
                    'nilai_budget' => $row['nilai_budget'] ?? $row['budget'] ?? null,
                    'sumber_dana' => $row['sumber_dana'] ?? null,
                    'keterangan_budget' => $row['keterangan_budget'] ?? null,
                    'pengelola_id' => $pengelolaId,
                    'tahun_pengadaan' => (int) ($row['tahun_pengadaan'] ?? date('Y')),
                    'nilai_pengadaan_total' => $row['nilai_pengadaan_total'] ?? 0,
                    'nilai_pengadaan_per_unit' => $row['nilai_pengadaan_per_unit'] ?? 0,
                    'kondisi_id' => $kondisiId,
                    'catatan' => $row['catatan'] ?? null,
                    'department_id' => $departmentId,
                    'is_active' => true,
                ];

                $service->createAset($data);
                $this->imported++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            'nama_aset' => 'required|string|max:200',
            'kategori' => 'required|string',
            'lokasi' => 'required|string',
            'kondisi' => 'required|string',
            'pengelola' => 'required|string',
            'jumlah_barang' => 'nullable|integer|min:1',
            'tahun_pengadaan' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'nilai_pengadaan_total' => 'nullable|numeric|min:0',
            'nilai_pengadaan_per_unit' => 'nullable|numeric|min:0',
        ];
    }

    public function getImportedCount(): int
    {
        return $this->imported;
    }

    public function getSkippedCount(): int
    {
        return $this->skipped;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function resolveForeignKey(string $table, string $column, ?string $value): ?int
    {
        if (empty($value)) {
            return null;
        }

        $value = trim($value);

        return DB::table($table)
            ->where($column, $value)
            ->orWhere($column, 'like', "%{$value}%")
            ->value('id');
    }
}
