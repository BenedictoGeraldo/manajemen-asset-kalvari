<?php

namespace App\Http\Controllers;

use App\Models\DataAsetKolektif;
use App\Models\MasterKategori;
use App\Models\MasterLokasi;
use App\Models\MasterKondisi;
use App\Models\MasterPengelola;
use App\Exports\DataAsetExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DataAsetKolektifController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = DataAsetKolektif::with(['kategori', 'lokasi', 'kondisi', 'pengelola'])
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_aset', 'like', "%$search%")
                  ->orWhere('kode_aset', 'like', "%$search%")
                  ->orWhereHas('kategori', function($k) use ($search) {
                      $k->where('nama_kategori', 'like', "%$search%");
                  })
                  ->orWhereHas('lokasi', function($l) use ($search) {
                      $l->where('nama_lokasi', 'like', "%$search%");
                  })
                  ->orWhereHas('pengelola', function($p) use ($search) {
                      $p->where('nama_pengelola', 'like', "%$search%");
                  });
            });
        }

        $asets = $query->paginate($perPage)->appends($request->except('page'));

        return view('master.data-aset.index', compact('asets', 'search', 'perPage'));
    }

    public function show(string $id)
    {
        $aset = DataAsetKolektif::with(['kategori', 'lokasi', 'kondisi', 'pengelola'])
            ->findOrFail($id);

        return view('master.data-aset.show', compact('aset'));
    }

    public function create()
    {
        $kategoris = MasterKategori::active()->orderBy('nama_kategori')->get();
        $lokasis = MasterLokasi::active()->orderBy('gedung')->orderBy('lantai')->get();
        $kondisis = MasterKondisi::active()->ordered()->get();
        $pengelolas = MasterPengelola::active()->orderBy('nama_pengelola')->get();

        return view('master.data-aset.create', compact('kategoris', 'lokasis', 'kondisis', 'pengelolas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_aset' => 'required|string|max:200',
            'kategori_id' => 'required|exists:master_kategori,id',
            'deskripsi_aset' => 'nullable|string',
            'ukuran' => 'nullable|string|max:100',
            'deskripsi_ukuran_bentuk' => 'nullable|string',
            'lokasi_id' => 'required|exists:master_lokasi,id',
            'kegunaan' => 'required|string',
            'keterangan_kegunaan' => 'nullable|string',
            'jumlah_barang' => 'required|integer|min:1',
            'tipe_grup' => 'required|in:individual,set,grup',
            'keterangan_tipe_grup' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'keterangan_budget' => 'nullable|string',
            'pengelola_id' => 'required|exists:master_pengelola,id',
            'tahun_pengadaan' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'nilai_pengadaan_total' => 'nullable|numeric|min:0',
            'nilai_pengadaan_per_unit' => 'nullable|numeric|min:0',
            'kondisi_id' => 'required|exists:master_kondisi,id',
        ]);

        DataAsetKolektif::create($validated);

        return redirect()->route('data-aset.index')
            ->with('success', 'Data aset berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $aset = DataAsetKolektif::findOrFail($id);
        $kategoris = MasterKategori::active()->orderBy('nama_kategori')->get();
        $lokasis = MasterLokasi::active()->orderBy('gedung')->orderBy('lantai')->get();
        $kondisis = MasterKondisi::active()->ordered()->get();
        $pengelolas = MasterPengelola::active()->orderBy('nama_pengelola')->get();

        return view('master.data-aset.edit', compact('aset', 'kategoris', 'lokasis', 'kondisis', 'pengelolas'));
    }

    public function update(Request $request, string $id)
    {
        $aset = DataAsetKolektif::findOrFail($id);

        $validated = $request->validate([
            'nama_aset' => 'required|string|max:200',
            'kategori_id' => 'required|exists:master_kategori,id',
            'deskripsi_aset' => 'nullable|string',
            'ukuran' => 'nullable|string|max:100',
            'deskripsi_ukuran_bentuk' => 'nullable|string',
            'lokasi_id' => 'required|exists:master_lokasi,id',
            'kegunaan' => 'required|string',
            'keterangan_kegunaan' => 'nullable|string',
            'jumlah_barang' => 'required|integer|min:1',
            'tipe_grup' => 'required|in:individual,set,grup',
            'keterangan_tipe_grup' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'keterangan_budget' => 'nullable|string',
            'pengelola_id' => 'required|exists:master_pengelola,id',
            'tahun_pengadaan' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'nilai_pengadaan_total' => 'nullable|numeric|min:0',
            'nilai_pengadaan_per_unit' => 'nullable|numeric|min:0',
            'kondisi_id' => 'required|exists:master_kondisi,id',
        ]);

        $aset->update($validated);

        return redirect()->route('data-aset.index')
            ->with('success', 'Data aset berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $aset = DataAsetKolektif::findOrFail($id);
        $aset->delete();

        return redirect()->route('data-aset.index')
            ->with('success', 'Data aset berhasil dihapus!');
    }

    public function export($format)
    {
        $timestamp = now()->format('Y-m-d_His');
        $filename = "data-aset_{$timestamp}.{$format}";

        if ($format === 'csv') {
            return Excel::download(new DataAsetExport, $filename, \Maatwebsite\Excel\Excel::CSV);
        }

        return Excel::download(new DataAsetExport, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }
}
