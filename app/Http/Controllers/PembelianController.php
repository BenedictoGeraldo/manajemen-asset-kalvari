<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePembelianRequest;
use App\Http\Requests\UpdatePembelianRequest;
use App\Services\MasterDataService;
use App\Services\PembelianService;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    protected $pembelianService;
    protected $masterDataService;

    public function __construct(PembelianService $pembelianService, MasterDataService $masterDataService)
    {
        $this->pembelianService = $pembelianService;
        $this->masterDataService = $masterDataService;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'per_page' => $request->input('per_page', 10),
        ];

        $pembelians = $this->pembelianService->getPaginatedPembelian($filters);

        return view('transaksi.pembelian.index', [
            'pembelians' => $pembelians,
            'search' => $filters['search'],
            'status' => $filters['status'],
            'perPage' => $filters['per_page'],
        ]);
    }

    public function create()
    {
        return view('transaksi.pembelian.create', $this->masterDataOptions());
    }

    public function store(StorePembelianRequest $request)
    {
        $this->pembelianService->create($request->validated(), auth()->id());

        return redirect()->route('transaksi.pembelian.index')
            ->with('success', 'Transaksi pembelian berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $pembelian = $this->pembelianService->getById((int) $id);

        return view('transaksi.pembelian.show', compact('pembelian'));
    }

    public function edit(string $id)
    {
        $pembelian = $this->pembelianService->getById((int) $id);

        if ($pembelian->status === 'disetujui') {
            return redirect()->route('transaksi.pembelian.show', $pembelian->id)
                ->with('error', 'Transaksi yang sudah disetujui tidak dapat diubah.');
        }

        return view('transaksi.pembelian.edit', [
            'pembelian' => $pembelian,
            ...$this->masterDataOptions(),
        ]);
    }

    public function update(UpdatePembelianRequest $request, string $id)
    {
        try {
            $this->pembelianService->update((int) $id, $request->validated(), auth()->id());
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('transaksi.pembelian.index')
            ->with('success', 'Transaksi pembelian berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        try {
            $this->pembelianService->delete((int) $id, auth()->id());
        } catch (\RuntimeException $e) {
            return redirect()->route('transaksi.pembelian.index')->with('error', $e->getMessage());
        }

        return redirect()->route('transaksi.pembelian.index')
            ->with('success', 'Transaksi pembelian berhasil dihapus.');
    }

    public function approve(string $id)
    {
        try {
            $this->pembelianService->approveAndPostToAset((int) $id, (int) auth()->id());
        } catch (\Throwable $e) {
            return redirect()->route('transaksi.pembelian.show', $id)
                ->with('error', 'Gagal menyetujui pembelian: ' . $e->getMessage());
        }

        return redirect()->route('transaksi.pembelian.show', $id)
            ->with('success', 'Pembelian disetujui dan berhasil diposting ke Data Aset.');
    }

    private function masterDataOptions(): array
    {
        return [
            'kategoris' => $this->masterDataService->getActiveKategoris(),
            'lokasis' => $this->masterDataService->getActiveLokasis(),
            'kondisis' => $this->masterDataService->getActiveKondisis(),
            'pengelolas' => $this->masterDataService->getActivePengelolas(),
        ];
    }
}
