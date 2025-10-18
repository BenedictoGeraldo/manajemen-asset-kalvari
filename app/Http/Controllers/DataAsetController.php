<?php

namespace App\Http\Controllers;

use App\Models\DataAset;
use Illuminate\Http\Request;

class DataAsetController extends Controller
{
    /**
     * Menampilkan halaman manajemen aset.
     */
    public function index()
    {
        $asets = DataAset::orderBy('id', 'desc')->paginate(10);
        
        // Memuat view utama untuk data aset
        return view('data-aset', compact('asets'));
    }

    public function create()
    {
        return redirect()->route('data-aset.index');
    }


    public function store(Request $request)
    {
        $request->validate([
            'kode_aset' => 'required|string|max:255|unique:data_aset',
            'nama_aset' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'lokasi' => 'required|string|max:255',
            'tahun_pendagaan' => 'required|digits:4|integer|min:1900|max:'.(date('Y')),
            'nilai_perolehan' => 'required|numeric|min:0',
        ]);

        DataAset::create($request->all());

        return redirect()->route('data-aset.index')
                         ->with('success', 'Aset baru berhasil ditambahkan.');
    }

    public function edit(DataAset $dataAset)
    {
         return redirect()->route('data-aset.index');
    }

    public function update(Request $request, DataAset $dataAset)
    {
        $request->validate([
            'nama_aset' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'lokasi' => 'required|string|max:255',
            'tahun_pendagaan' => 'required|digits:4|integer|min:1900|max:'.(date('Y')),
            'nilai_perolehan' => 'required|numeric|min:0',
        ]);

        // 'kode_aset' tidak diupdate karena biasanya unik dan permanen
        $dataAset->update($request->except('kode_aset'));

        return redirect()->route('data-aset.index')
                         ->with('success', 'Data aset berhasil diperbarui.');
    }

    public function destroy(DataAset $dataAset)
    {
        $dataAset->delete();

        return redirect()->route('data-aset.index')
                         ->with('success', 'Aset berhasil dihapus.');
    }
}

