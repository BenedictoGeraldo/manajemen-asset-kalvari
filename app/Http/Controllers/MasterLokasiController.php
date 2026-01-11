<?php

namespace App\Http\Controllers;

use App\Models\MasterLokasi;
use App\Exports\MasterLokasiExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MasterLokasiController extends Controller
{
    public function index()
    {
        $lokasis = MasterLokasi::withCount('dataAset')->orderBy('gedung')->orderBy('lantai')->get();
        return view('master.lokasi.index', compact('lokasis'));
    }

    public function create()
    {
        return view('master.lokasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:100',
            'keterangan_lokasi' => 'nullable|string',
            'gedung' => 'required|string|max:50',
            'lantai' => 'nullable|string|max:20',
            'ruangan' => 'nullable|string|max:50',
            'is_active' => 'boolean'
        ]);

        MasterLokasi::create($validated);

        return redirect()->route('master.lokasi.index')
            ->with('success', 'Lokasi berhasil ditambahkan!');
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
            'nama_lokasi' => 'required|string|max:100',
            'keterangan_lokasi' => 'nullable|string',
            'gedung' => 'required|string|max:50',
            'lantai' => 'nullable|string|max:20',
            'ruangan' => 'nullable|string|max:50',
            'is_active' => 'boolean'
        ]);

        $lokasi->update($validated);

        return redirect()->route('master.lokasi.index')
            ->with('success', 'Lokasi berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $lokasi = MasterLokasi::findOrFail($id);

        if ($lokasi->dataAset()->count() > 0) {
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
        $filename = "master-lokasi_{$timestamp}.{$format}";

        if ($format === 'csv') {
            return Excel::download(new MasterLokasiExport, $filename, \Maatwebsite\Excel\Excel::CSV);
        }

        return Excel::download(new MasterLokasiExport, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }
}
