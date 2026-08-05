<?php

namespace App\Http\Controllers;

use App\Models\DataAsetKolektif;
use App\Models\TransaksiPemusnahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemusnahanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $pemusnahans = TransaksiPemusnahan::with('aset')
            ->orderByDesc('created_at')
            ->search($search)
            ->paginate(10)
            ->withQueryString();

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
            'nama_pengaju' => 'nullable|string|max:255',
            'unit_pengaju' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $aset = DataAsetKolektif::findOrFail($request->aset_id);

        if ($aset->jumlah_barang < $request->jumlah_dimusnahkan) {
            return back()->with('error', 'Jumlah barang yang dimusnahkan melebihi stok yang ada.')->withInput();
        }

        DB::beginTransaction();
        try {
            TransaksiPemusnahan::create($request->only([
                'aset_id', 'jumlah_dimusnahkan', 'tanggal_pemusnahan',
                'alasan_pemusnahan', 'metode_pemusnahan', 'penanggung_jawab',
                'nama_pengaju', 'unit_pengaju', 'catatan',
            ]));

            $aset->jumlah_barang -= $request->jumlah_dimusnahkan;
            if ($aset->jumlah_barang == 0) {
                $aset->is_active = false;
            }
            $aset->save();

            DB::commit();
            return redirect()->route('transaksi.pemusnahan.index')->with('success', 'Transaksi pemusnahan berhasil dicatat.');
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

    public function destroy($id)
    {
        $pemusnahan = TransaksiPemusnahan::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($pemusnahan->aset) {
                $aset = $pemusnahan->aset;
                $aset->jumlah_barang += $pemusnahan->jumlah_dimusnahkan;
                if ($aset->jumlah_barang > 0) {
                    $aset->is_active = true;
                }
                $aset->save();
            }

            $pemusnahan->delete();
            DB::commit();
            return redirect()->route('transaksi.pemusnahan.index')->with('success', 'Transaksi pemusnahan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
