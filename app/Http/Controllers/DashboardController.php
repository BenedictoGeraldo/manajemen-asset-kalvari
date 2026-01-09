<?php

namespace App\Http\Controllers;

use App\Models\DataAsetKolektif;
use App\Models\MasterKategori;
use App\Models\MasterLokasi;
use App\Models\MasterKondisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Menghitung Total Aset
        $totalAset = DataAsetKolektif::sum('jumlah_barang');

        // 2. Menghitung Total Nilai Pengadaan Aset
        $totalNilai = DataAsetKolektif::sum('nilai_pengadaan_total');

        // 3. Menghitung Jumlah Record Aset
        $totalRecord = DataAsetKolektif::count();

        // 4. Menghitung Jumlah Kategori
        $totalKategori = MasterKategori::active()->count();

        // 5. Menghitung Jumlah Lokasi
        $totalLokasi = MasterLokasi::active()->count();

        // 6. Aset Terbaru (5 aset terakhir)
        $asetTerbaru = DataAsetKolektif::with(['kategori', 'lokasi', 'kondisi'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 7. Distribusi per Kondisi
        $distribusiKondisi = DataAsetKolektif::select('kondisi_id', DB::raw('count(*) as total'))
            ->with('kondisi')
            ->groupBy('kondisi_id')
            ->get();

        // 8. Distribusi per Kategori (Top 5)
        $distribusiKategori = DataAsetKolektif::select('kategori_id', DB::raw('count(*) as total'))
            ->with('kategori')
            ->groupBy('kategori_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // Mengirim semua data ke view 'dashboard'
        return view('dashboard', compact(
            'totalAset',
            'totalNilai',
            'totalRecord',
            'totalKategori',
            'totalLokasi',
            'asetTerbaru',
            'distribusiKondisi',
            'distribusiKategori'
        ));
    }
}
