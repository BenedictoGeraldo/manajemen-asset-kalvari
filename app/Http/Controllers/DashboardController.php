<?php

namespace App\Http\Controllers;

use App\Models\MasterKategori;
use App\Models\MasterLokasi;
use App\Services\DataAsetService;

class DashboardController extends Controller
{
    protected $dataAsetService;

    public function __construct(DataAsetService $dataAsetService)
    {
        $this->dataAsetService = $dataAsetService;
    }

    public function index()
    {
        // Statistik dashboard (di-cache 1 jam)
        $stats = $this->dataAsetService->getDashboardStats();

        // Tren penambahan aset 12 bulan terakhir — via Service bukan langsung di Controller
        $trend = $this->dataAsetService->getMonthlyTrend(12);

        // Menghitung Jumlah Kategori & Lokasi aktif
        $totalKategori = MasterKategori::active()->count();
        $totalLokasi   = MasterLokasi::active()->count();

        // 5 Aset Terbaru — hindari SELECT * karena ada kolom gambar_aset_base64 yang besar
        $asetTerbaru = \App\Models\DataAsetKolektif::select(
                'id', 'kode_aset', 'nama_aset', 'kategori_id',
                'lokasi_id', 'kondisi_id', 'jumlah_barang', 'is_active', 'created_at'
            )
            ->with([
                'kategori:id,nama_kategori',
                'lokasi:id,nama_lokasi',
                'kondisi:id,nama_kondisi',
            ])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('dashboard', [
            'totalAset'          => $stats['total_aset'],
            'totalNilai'         => $stats['total_nilai'],
            'totalRecord'        => $stats['total_record'],
            'totalKategori'      => $totalKategori,
            'totalLokasi'        => $totalLokasi,
            'asetTerbaru'        => $asetTerbaru,
            'distribusiKondisi'  => $stats['distribusi_kondisi'],
            'distribusiKategori' => $stats['distribusi_kategori'],
            'trendLabels'        => $trend['labels'],
            'trendValues'        => $trend['values'],
        ]);
    }
}
