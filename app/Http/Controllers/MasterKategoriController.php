<?php

namespace App\Http\Controllers;

use App\Models\MasterKategori;
use App\Services\MasterDataService;
use App\Http\Requests\StoreMasterKategoriRequest;
use App\Http\Requests\UpdateMasterKategoriRequest;
use App\Exports\MasterKategoriExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MasterKategoriController extends Controller
{
    protected $masterDataService;

    public function __construct(MasterDataService $masterDataService)
    {
        $this->masterDataService = $masterDataService;
    }

    public function index()
    {
        $kategoris = MasterKategori::withCount('dataAset')->orderBy('nama_kategori')->get();
        return view('master.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('master.kategori.create');
    }

    public function store(StoreMasterKategoriRequest $request)
    {
        MasterKategori::create($request->validated());

        // Clear cache
        $this->masterDataService->clearMasterDataCache();

        return redirect()->route('master.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $kategori = MasterKategori::findOrFail($id);
        return view('master.kategori.edit', compact('kategori'));
    }

    public function update(UpdateMasterKategoriRequest $request, string $id)
    {
        $kategori = MasterKategori::findOrFail($id);
        $kategori->update($request->validated());

        // Clear cache
        $this->masterDataService->clearMasterDataCache();

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

        // Clear cache
        $this->masterDataService->clearMasterDataCache();

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
