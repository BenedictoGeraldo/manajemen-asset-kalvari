<?php

namespace App\Http\Controllers;

use App\Models\MasterLokasi;
use App\Exports\MasterLokasiExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MasterLokasiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $lokasis = MasterLokasi::withCount('dataAset')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lokasi', 'like', "%{$search}%")
                      ->orWhere('sub_lokasi', 'like', "%{$search}%")
                      ->orWhere('kode_lokasi', 'like', "%{$search}%")
                      ->orWhere('keterangan_lokasi', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama_lokasi')
            ->orderBy('sub_lokasi')
            ->get();

        return view('master.lokasi.index', compact('lokasis', 'search'));
    }

    public function create()
    {
        return view('master.lokasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_lokasi'      => 'required|string|max:10|unique:master_lokasi,kode_lokasi',
            'nama_lokasi'      => 'required|string|max:150',
            'sub_lokasi'       => 'nullable|string|max:150',
            'keterangan_lokasi'=> 'nullable|string|max:500',
            'is_active'        => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['kode_lokasi'] = strtoupper($validated['kode_lokasi']);

        MasterLokasi::create($validated);

        return redirect()->route('master.lokasi.index')
            ->with('success', "Lokasi berhasil ditambahkan!");
    }

    public function edit(string $id)
    {
        $lokasi = MasterLokasi::findOrFail($id);
        return view('master.lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, string $id)
    {
        $lokasi = MasterLokasi::findOrFail($id);

        $validated = $request->validate([
            'kode_lokasi'      => "required|string|max:10|unique:master_lokasi,kode_lokasi,{$id}",
            'nama_lokasi'      => 'required|string|max:150',
            'sub_lokasi'       => 'nullable|string|max:150',
            'keterangan_lokasi'=> 'nullable|string|max:500',
            'is_active'        => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['kode_lokasi'] = strtoupper($validated['kode_lokasi']);

        if (!$validated['is_active'] && $lokasi->is_active && $lokasi->dataAset()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Lokasi tidak dapat dinonaktifkan karena masih digunakan oleh aset!');
        }

        $lokasi->update($validated);

        return redirect()->route('master.lokasi.index')
            ->with('success', "Lokasi berhasil diperbarui!");
    }

    public function destroy(string $id)
    {
        $lokasi = MasterLokasi::withCount('dataAset')->findOrFail($id);

        if ($lokasi->data_aset_count > 0) {
            return redirect()->route('master.lokasi.index')
                ->with('error', 'Lokasi tidak dapat dihapus karena masih digunakan oleh aset!');
        }

        $lokasi->delete();

        return redirect()->route('master.lokasi.index')
            ->with('success', 'Lokasi berhasil dihapus!');
    }

    public function export($format)
    {
        $timestamp = now()->format('Y-m-d_His');
        $filename  = "master-lokasi_{$timestamp}.{$format}";

        if ($format === 'csv') {
            return Excel::download(new MasterLokasiExport, $filename, \Maatwebsite\Excel\Excel::CSV);
        }

        return Excel::download(new MasterLokasiExport, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }
}
