<?php

namespace App\Http\Controllers;

use App\Models\MasterPengelola;
use App\Exports\MasterPengelolaExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MasterPengelolaController extends Controller
{
    public function index()
    {
        $pengelolas = MasterPengelola::withCount('dataAset')->orderBy('nama_pengelola')->get();
        return view('master.pengelola.index', compact('pengelolas'));
    }

    public function create()
    {
        return view('master.pengelola.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengelola' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'departemen' => 'required|string|max:100',
            'kontak' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'is_active' => 'boolean'
        ]);

        MasterPengelola::create($validated);

        return redirect()->route('master.pengelola.index')
            ->with('success', 'Pengelola berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $pengelola = MasterPengelola::findOrFail($id);
        return view('master.pengelola.edit', compact('pengelola'));
    }

    public function update(Request $request, string $id)
    {
        $pengelola = MasterPengelola::findOrFail($id);

        $validated = $request->validate([
            'nama_pengelola' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'departemen' => 'required|string|max:100',
            'kontak' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'is_active' => 'boolean'
        ]);

        $pengelola->update($validated);

        return redirect()->route('master.pengelola.index')
            ->with('success', 'Pengelola berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $pengelola = MasterPengelola::findOrFail($id);

        if ($pengelola->dataAset()->count() > 0) {
            return redirect()->route('master.pengelola.index')
                ->with('error', 'Pengelola tidak dapat dihapus karena masih digunakan oleh aset!');
        }

        $pengelola->delete();

        return redirect()->route('master.pengelola.index')
            ->with('success', 'Pengelola berhasil dihapus!');
    }

    public function export($format)
    {
        $timestamp = now()->format('Y-m-d_His');
        $filename = "master-pengelola_{$timestamp}.{$format}";

        if ($format === 'csv') {
            return Excel::download(new MasterPengelolaExport, $filename, \Maatwebsite\Excel\Excel::CSV);
        }

        return Excel::download(new MasterPengelolaExport, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }
}
