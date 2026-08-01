<?php

namespace App\Http\Controllers;

use App\Models\MasterPengelola;
use App\Models\Department;
use App\Exports\MasterPengelolaExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MasterPengelolaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $pengelolas = MasterPengelola::with(['department'])->withCount('dataAset')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_pengelola', 'like', "%{$search}%")
                        ->orWhere('jabatan', 'like', "%{$search}%")
                        ->orWhereHas('department', function($d) use ($search) {
                            $d->where('name', 'like', "%{$search}%");
                        })
                        ->orWhere('kontak', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama_pengelola')
            ->get();

        return view('master.pengelola.index', compact('pengelolas', 'search'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('master.pengelola.create', compact('departments'));
    }

    public function show(string $id)
    {
        $pengelola = MasterPengelola::with(['department', 'dataAset.kategori', 'dataAset.lokasi', 'dataAset.kondisi'])
            ->withCount('dataAset')
            ->findOrFail($id);

        return view('master.pengelola.show', compact('pengelola'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengelola' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'kontak' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        MasterPengelola::create($validated);

        return redirect()->route('master.pengelola.index')
            ->with('success', 'Pengelola berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $pengelola = MasterPengelola::findOrFail($id);
        $departments = Department::orderBy('name')->get();
        return view('master.pengelola.edit', compact('pengelola', 'departments'));
    }

    public function update(Request $request, string $id)
    {
        $pengelola = MasterPengelola::findOrFail($id);

        $validated = $request->validate([
            'nama_pengelola' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'kontak' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if (!$validated['is_active'] && $pengelola->is_active && $pengelola->dataAset()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Pengelola tidak dapat dinonaktifkan karena masih digunakan oleh aset!');
        }

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
