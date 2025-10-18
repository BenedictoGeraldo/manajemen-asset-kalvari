<?php

namespace App\Http\Controllers;

use App\Models\DataAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Menghitung Total Aset
        $totalAset = DataAset::count();

        // 2. Menghitung Total Nilai Perolehan Aset
        $totalNilai = DataAset::sum('nilai_perolehan');

        // 3. Menghitung Jumlah Aset dalam Kondisi Baik
        $asetBaik = DataAset::where('kondisi', 'Baik')->count();

        // 4. Menghitung Jumlah Aset yang Perlu Perhatian
        $asetPerluPerbaikan = DataAset::where('kondisi', '!=', 'Baik')->count();

        // 5. Menghitung Jumlah Kategori Aset yang unik
        $totalKategori = DataAset::distinct()->count('kategori');

        // BARU: 6. Menghitung tahun perolehan aset terbaru
        $asetTerbaruTahun = DataAset::max('tahun_pengadaan');

        // Mengirim semua data ke view 'dashboard'
        return view('dashboard', compact(
            'totalAset',
            'totalNilai',
            'asetBaik',
            'asetPerluPerbaikan',
            'totalKategori',
            'asetTerbaruTahun'
        ));
    }
}
