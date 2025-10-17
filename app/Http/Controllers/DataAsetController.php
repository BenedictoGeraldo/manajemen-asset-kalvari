<?php

namespace App\Http\Controllers;

use App\Models\DataAset;
use Illuminate\Http\Request;

class DataAsetController extends Controller
{
    /**
     * Menampilkan halaman manajemen aset (view, create, edit via modal).
     */
    public function index()
    {
        $asets = DataAset::latest()->paginate(10);
        // Mengubah path view ke 'data-aset' sesuai dengan nama file baru Anda
        return view('data-aset', compact('asets'));
    }

    /**
     * Method ini tidak lagi digunakan untuk menampilkan view,
     * karena form tambah sekarang ada di dalam modal di halaman index.
     * Dibiarkan ada untuk konsistensi dengan route resource.
     */
    public function create()
    {
        return redirect()->route('data-aset.index');
    }

    /**
     * Menyimpan data aset baru ke dalam database.
     */
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

    /**
     * Method ini tidak lagi digunakan untuk menampilkan view,
     * karena form edit sekarang ada di dalam modal di halaman index.
     * Dibiarkan ada untuk konsistensi dengan route resource.
     */
    public function edit(DataAset $dataAset)
    {
         return redirect()->route('data-aset.index');
    }

    /**
     * Memperbarui data aset di dalam database.
     */
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

        $dataAset->update($request->except('kode_aset'));

        return redirect()->route('data-aset.index')
                         ->with('success', 'Data aset berhasil diperbarui.');
    }

    /**
     * Menghapus data aset dari database.
     */
    public function destroy(DataAset $dataAset)
    {
        $dataAset->delete();

        return redirect()->route('data-aset.index')
                         ->with('success', 'Aset berhasil dihapus.');
    }
}

