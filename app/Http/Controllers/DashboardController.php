<?php

namespace App\Http\Controllers;

use App\Models\DataAsetKolektif;
use App\Models\MasterKategori;
use App\Models\MasterLokasi;
use App\Services\DataAsetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        ]);
    }
}
