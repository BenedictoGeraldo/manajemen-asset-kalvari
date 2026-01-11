<?php

namespace App\Http\Controllers;

use App\Models\MasterKategori;
use App\Exports\MasterKategoriExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MasterKategoriController extends Controller
{
    public function index()
    {
        $kategoris = MasterKategori::withCount('dataAset')->orderBy('nama_kategori')->get();
        return view('master.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('master.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:master_kategori',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        MasterKategori::create($validated);

        return redirect()->route('master.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $kategori = MasterKategori::findOrFail($id);
        return view('master.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, string $id)
    {
        $kategori = MasterKategori::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:master_kategori,nama_kategori,' . $id,
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $kategori->update($validated);

        return redirect()->route('master.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $kategori = MasterKategori::findOrFail($id);

        if ($kategori->dataAset()->count() > 0) {
            return redirect()->route('master.kategori.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh aset!');
        }

        $kategori->delete();

        return redirect()->route('master.kategori.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    public function export($format)
    {
        $timestamp = now()->format('Y-m-d_His');
        $filename = "master-kategori_{$timestamp}.{$format}";

        if ($format === 'csv') {
            return Excel::download(new MasterKategoriExport, $filename, \Maatwebsite\Excel\Excel::CSV);
        }

        return Excel::download(new MasterKategoriExport, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }
}
