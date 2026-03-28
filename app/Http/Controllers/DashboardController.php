<?php

namespace App\Http\Controllers;

use App\Models\DataAsetKolektif;
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
        // Get cached dashboard statistics
        $stats = $this->dataAsetService->getDashboardStats();

        // 1 chart penting: tren penambahan aset 12 bulan terakhir
        $startMonth = now()->startOfMonth()->subMonths(11);
        $monthlyTrendRaw = DataAsetKolektif::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, SUM(jumlah_barang) as total_barang")
            ->where('created_at', '>=', $startMonth)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total_barang', 'bulan');

        $trendLabels = [];
        $trendValues = [];

        for ($i = 0; $i < 12; $i++) {
            $month = $startMonth->copy()->addMonths($i);
            $monthKey = $month->format('Y-m');

            $trendLabels[] = $month->isoFormat('MMM YY');
            $trendValues[] = (int) ($monthlyTrendRaw[$monthKey] ?? 0);
        }

        // 4. Menghitung Jumlah Kategori
        $totalKategori = MasterKategori::active()->count();

        // 5. Menghitung Jumlah Lokasi
        $totalLokasi = MasterLokasi::active()->count();

        // 6. Aset Terbaru (5 aset terakhir)
        $asetTerbaru = DataAsetKolektif::with(['kategori', 'lokasi', 'kondisi'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Mengirim semua data ke view 'dashboard'
        return view('dashboard', [
            'totalAset' => $stats['total_aset'],
            'totalNilai' => $stats['total_nilai'],
            'totalRecord' => $stats['total_record'],
            'totalKategori' => $totalKategori,
            'totalLokasi' => $totalLokasi,
            'asetTerbaru' => $asetTerbaru,
            'distribusiKondisi' => $stats['distribusi_kondisi'],
            'distribusiKategori' => $stats['distribusi_kategori'],
            'trendLabels' => $trendLabels,
            'trendValues' => $trendValues,
        ]);
    }
}
