<?php

namespace App\Http\Controllers;

use App\Models\DataAsetKolektif;
use App\Models\TransaksiPemusnahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemusnahanController extends Controller
{
    public function index()
    {
        $pemusnahans = TransaksiPemusnahan::with('aset')->latest()->paginate(10);
        return view('transaksi.pemusnahan.index', compact('pemusnahans'));
    }

    public function create()
    {
        $asets = DataAsetKolektif::where('is_active', true)->get();
        return view('transaksi.pemusnahan.create', compact('asets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'aset_id' => 'required|exists:data_aset_kolektif,id',
            'jumlah_dimusnahkan' => 'required|integer|min:1',
            'tanggal_pemusnahan' => 'required|date',
            'alasan_pemusnahan' => 'required|string|max:255',
            'metode_pemusnahan' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $aset = DataAsetKolektif::findOrFail($request->aset_id);

        if ($aset->jumlah_barang < $request->jumlah_dimusnahkan) {
            return back()->with('error', 'Jumlah barang yang dimusnahkan melebihi stok yang ada.')->withInput();
        }

        DB::beginTransaction();
        try {
            TransaksiPemusnahan::create($request->all());

            // Kurangi stok barang
            $aset->jumlah_barang -= $request->jumlah_dimusnahkan;
            if ($aset->jumlah_barang == 0) {
                $aset->is_active = false;
            }
            $aset->save();

            DB::commit();
            return redirect()->route('pemusnahan.index')->with('success', 'Transaksi pemusnahan berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(TransaksiPemusnahan $pemusnahan)
    {
        $pemusnahan->load('aset');
        return view('transaksi.pemusnahan.show', compact('pemusnahan'));
    }
}
