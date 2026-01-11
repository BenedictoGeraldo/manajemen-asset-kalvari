<?php

namespace App\Http\Controllers;

use App\Models\MasterKondisi;
use App\Exports\MasterKondisiExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MasterKondisiController extends Controller
{
    public function index()
    {
        $kondisis = MasterKondisi::withCount('dataAset')->ordered()->get();
        return view('master.kondisi.index', compact('kondisis'));
    }

    public function create()
    {
        return view('master.kondisi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kondisi' => 'required|string|max:50|unique:master_kondisi',
            'keterangan' => 'nullable|string',
            'kode_warna' => 'required|string|max:20',
            'urutan' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        MasterKondisi::create($validated);

        return redirect()->route('master.kondisi.index')
            ->with('success', 'Kondisi berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $kondisi = MasterKondisi::findOrFail($id);
        return view('master.kondisi.edit', compact('kondisi'));
    }

    public function update(Request $request, string $id)
    {
        $kondisi = MasterKondisi::findOrFail($id);

        $validated = $request->validate([
            'nama_kondisi' => 'required|string|max:50|unique:master_kondisi,nama_kondisi,' . $id,
            'keterangan' => 'nullable|string',
            'kode_warna' => 'required|string|max:20',
            'urutan' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $kondisi->update($validated);

        return redirect()->route('master.kondisi.index')
            ->with('success', 'Kondisi berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $kondisi = MasterKondisi::findOrFail($id);

        if ($kondisi->dataAset()->count() > 0) {
            return redirect()->route('master.kondisi.index')
                ->with('error', 'Kondisi tidak dapat dihapus karena masih digunakan oleh aset!');
        }

        $kondisi->delete();

        return redirect()->route('master.kondisi.index')
            ->with('success', 'Kondisi berhasil dihapus!');
    }

    public function export($format)
    {
        $timestamp = now()->format('Y-m-d_His');
        $filename = "master-kondisi_{$timestamp}.{$format}";

        if ($format === 'csv') {
            return Excel::download(new MasterKondisiExport, $filename, \Maatwebsite\Excel\Excel::CSV);
        }

        return Excel::download(new MasterKondisiExport, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }
}
