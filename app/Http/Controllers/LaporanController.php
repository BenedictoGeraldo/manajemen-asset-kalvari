<?php

namespace App\Http\Controllers;

use App\Exports\LaporanDataAsetExport;
use App\Exports\LaporanTransaksiPembelianExport;
use App\Exports\TransaksiMutasiAsetExport;
use App\Models\DataAsetKolektif;
use App\Models\TransaksiPembelian;
use App\Models\TransaksiMutasiAset;
use App\Models\TransaksiPemusnahan;
use App\Services\MasterDataService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function __construct(private MasterDataService $masterDataService)
    {
    }

    public function dataAset(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'kategori_id' => $request->input('kategori_id'),
            'lokasi_id' => $request->input('lokasi_id'),
            'kondisi_id' => $request->input('kondisi_id'),
            'status' => $request->input('status'),
        ];

        $laporanAset = $this->buildDataAsetQuery($filters)
            ->paginate(10)
            ->appends($request->query());

        $kategoris = $this->masterDataService->getActiveKategoris();
        $lokasis = $this->masterDataService->getActiveLokasis();
        $kondisis = $this->masterDataService->getActiveKondisis();

        return view('laporan.data-aset.index', compact('laporanAset', 'filters', 'kategoris', 'lokasis', 'kondisis'));
    }

    public function exportDataAset(Request $request, string $format)
    {
        $filters = [
            'search' => $request->input('search'),
            'kategori_id' => $request->input('kategori_id'),
            'lokasi_id' => $request->input('lokasi_id'),
            'kondisi_id' => $request->input('kondisi_id'),
            'status' => $request->input('status'),
        ];

        $laporanAset = $this->buildDataAsetQuery($filters)->get();

        $extension = $format === 'csv' ? 'csv' : 'xlsx';
        $writerType = $extension === 'csv'
            ? \Maatwebsite\Excel\Excel::CSV
            : \Maatwebsite\Excel\Excel::XLSX;
        $filename = 'laporan-data-aset-' . now()->format('Ymd-His') . '.' . $extension;

        return Excel::download(new LaporanDataAsetExport($laporanAset), $filename, $writerType);
    }

    public function mutasiAset(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'jenis' => $request->input('jenis'),
        ];

        $laporanMutasi = $this->buildMutasiAsetQuery($filters)
            ->paginate(10)
            ->appends($request->query());

        return view('laporan.mutasi-aset.index', compact('laporanMutasi', 'filters'));
    }

    public function exportMutasiAset(Request $request, string $format)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'jenis' => $request->input('jenis'),
        ];

        $laporanMutasi = $this->buildMutasiAsetQuery($filters)->get();

        $extension = $format === 'csv' ? 'csv' : 'xlsx';
        $writerType = $extension === 'csv'
            ? \Maatwebsite\Excel\Excel::CSV
            : \Maatwebsite\Excel\Excel::XLSX;
        $filename = 'laporan-mutasi-aset-' . now()->format('Ymd-His') . '.' . $extension;

        return Excel::download(new TransaksiMutasiAsetExport($laporanMutasi), $filename, $writerType);
    }

    public function pembelian(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
        ];

        $laporanPembelian = $this->buildPembelianQuery($filters)
            ->paginate(10)
            ->appends($request->query());

        return view('laporan.pembelian.index', compact('laporanPembelian', 'filters'));
    }

    public function exportPembelian(Request $request, string $format)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
        ];

        $laporanPembelian = $this->buildPembelianQuery($filters)->get();

        $extension = $format === 'csv' ? 'csv' : 'xlsx';
        $writerType = $extension === 'csv'
            ? \Maatwebsite\Excel\Excel::CSV
            : \Maatwebsite\Excel\Excel::XLSX;
        $filename = 'laporan-pembelian-' . now()->format('Ymd-His') . '.' . $extension;

        return Excel::download(new LaporanTransaksiPembelianExport($laporanPembelian), $filename, $writerType);
    }

    public function pemusnahan(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'metode_pemusnahan' => $request->input('metode_pemusnahan'),
        ];

        $laporanPemusnahan = $this->buildPemusnahanQuery($filters)
            ->paginate(10)
            ->appends($request->query());

        $metodeList = TransaksiPemusnahan::METODE_LIST;

        return view('laporan.pemusnahan.index', compact('laporanPemusnahan', 'filters', 'metodeList'));
    }

    public function exportPemusnahan(Request $request, string $format)
    {
        $filters = [
            'search' => $request->input('search'),
            'metode_pemusnahan' => $request->input('metode_pemusnahan'),
        ];

        $laporanPemusnahan = $this->buildPemusnahanQuery($filters)->get();

        $extension = $format === 'csv' ? 'csv' : 'xlsx';
        $writerType = $extension === 'csv'
            ? \Maatwebsite\Excel\Excel::CSV
            : \Maatwebsite\Excel\Excel::XLSX;
        $filename = 'laporan-pemusnahan-' . now()->format('Ymd-His') . '.' . $extension;

        return Excel::download(new \App\Exports\LaporanPemusnahanExport($laporanPemusnahan), $filename, $writerType);
    }

    private function buildPemusnahanQuery(array $filters): Builder
    {
        $search = trim((string) ($filters['search'] ?? ''));

        return TransaksiPemusnahan::query()
            ->with(['aset:id,kode_aset,nama_aset'])
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->whereHas('aset', function (Builder $asetQuery) use ($search) {
                    $asetQuery->where('nama_aset', 'like', "%{$search}%")
                        ->orWhere('kode_aset', 'like', "%{$search}%");
                })
                ->orWhere('kode_transaksi', 'like', "%{$search}%")
                ->orWhere('alasan_pemusnahan', 'like', "%{$search}%")
                ->orWhere('metode_pemusnahan', 'like', "%{$search}%")
                ->orWhere('penanggung_jawab', 'like', "%{$search}%");
            })
            ->when($filters['metode_pemusnahan'] ?? null, function (Builder $query, $metode) {
                $query->where('metode_pemusnahan', $metode);
            })
            ->orderByDesc('tanggal_pemusnahan');
    }

    private function buildDataAsetQuery(array $filters): Builder
    {
        $search = trim((string) ($filters['search'] ?? ''));

        return DataAsetKolektif::query()
            ->with([
                'kategori:id,nama_kategori', 
                'lokasi:id,nama_lokasi,sub_lokasi', 
                'kondisi:id,nama_kondisi', 
                'pengelola:id,nama_pengelola'
            ])
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $subQuery) use ($search) {
                    $subQuery->where('nama_aset', 'like', "%{$search}%")
                        ->orWhere('kode_aset', 'like', "%{$search}%")
                        ->orWhereHas('kategori', function (Builder $kategoriQuery) use ($search) {
                            $kategoriQuery->where('nama_kategori', 'like', "%{$search}%");
                        })
                        ->orWhereHas('lokasi', function (Builder $lokasiQuery) use ($search) {
                            $lokasiQuery->where('nama_lokasi', 'like', "%{$search}%")
                                ->orWhere('sub_lokasi', 'like', "%{$search}%");
                        })
                        ->orWhereHas('pengelola', function (Builder $pengelolaQuery) use ($search) {
                            $pengelolaQuery->where('nama_pengelola', 'like', "%{$search}%");
                        });
                });
            })
            ->when($filters['kategori_id'] ?? null, function (Builder $query, $kategoriId) {
                $query->where('kategori_id', $kategoriId);
            })
            ->when($filters['lokasi_id'] ?? null, function (Builder $query, $lokasiId) {
                $query->where('lokasi_id', $lokasiId);
            })
            ->when($filters['kondisi_id'] ?? null, function (Builder $query, $kondisiId) {
                $query->where('kondisi_id', $kondisiId);
            })
            ->when(isset($filters['status']) && $filters['status'] !== '' && $filters['status'] !== null, function (Builder $query) use ($filters) {
                $query->where('is_active', $filters['status'] === 'aktif');
            })
            ->orderBy('kode_aset');
    }

    private function buildMutasiAsetQuery(array $filters): Builder
    {
        return TransaksiMutasiAset::query()
            ->with([
                'aset:id,kode_aset,nama_aset', 
                'lokasiLama:id,nama_lokasi,sub_lokasi', 
                'lokasiBaru:id,nama_lokasi,sub_lokasi'
            ])
            ->search($filters['search'] ?? null)
            ->when($filters['status'] ?? null, function (Builder $query, $status) {
                $query->where('status', $status);
            })
            ->when($filters['jenis'] ?? null, function (Builder $query, $jenis) {
                $query->where('jenis_mutasi', $jenis);
            })
            ->orderByDesc('tanggal_mutasi')
            ->orderByDesc('created_at');
    }

    private function buildPembelianQuery(array $filters): Builder
    {
        return TransaksiPembelian::query()
            ->withCount('items')
            ->search($filters['search'] ?? null)
            ->when($filters['status'] ?? null, function (Builder $query, $status) {
                $query->where('status', $status);
            })
            ->orderByDesc('tanggal_pembelian')
            ->orderByDesc('created_at');
    }
}
