<?php

namespace App\Http\Controllers;

use App\Models\DataAsetKolektif;
use App\Models\Department;
use App\Models\MasterLokasi;
use App\Models\MasterKategori;
use Illuminate\Http\Request;

class PrintAsetController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('name')->get();
        $lokasis = MasterLokasi::orderBy('nama_lokasi')->get();
        $kategoris = MasterKategori::orderBy('nama_kategori')->get();

        return view('laporan.print-qr.index', compact('departments', 'lokasis', 'kategoris'));
    }

    public function generate(Request $request)
    {
        $query = DataAsetKolektif::with(['lokasi', 'department', 'kategori'])
            ->active();

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $assets = $query->orderBy('kode_aset')->get();

        return view('laporan.print-qr.print', compact('assets'));
    }
}
