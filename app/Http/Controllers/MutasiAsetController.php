<?php

namespace App\Http\Controllers;

use App\Exports\TransaksiMutasiAsetExport;
use App\Http\Requests\StoreMutasiAsetRequest;
use App\Http\Requests\UpdateMutasiAsetRequest;
use App\Http\Requests\CompleteMutasiAsetRequest;
use App\Models\TransaksiMutasiAset;
use App\Models\DataAsetKolektif;
use App\Services\MutasiAsetService;
use App\Services\MasterDataService;
use Maatwebsite\Excel\Facades\Excel;

class MutasiAsetController extends Controller
{
    public function __construct(
        private MutasiAsetService $mutasiService,
        private MasterDataService $masterDataService,
    ) {}

    public function index()
    {
        $filters = request()->only(['search', 'status', 'jenis', 'per_page']);
        $mutasis = $this->mutasiService->getPaginatedMutasi($filters);

        return view('transaksi.mutasi_aset.index', compact('mutasis', 'filters'));
    }

    public function export(string $format)
    {
        $filters = request()->only(['search', 'status', 'jenis']);
        $mutasis = TransaksiMutasiAset::with(['aset'])
            ->search($filters['search'] ?? null)
            ->when($filters['status'] ?? null, fn($q) => $q->where('status', $filters['status']))
            ->when($filters['jenis'] ?? null, fn($q) => $q->where('jenis_mutasi', $filters['jenis']))
            ->get();

        return Excel::download(
            new TransaksiMutasiAsetExport($mutasis),
            'mutasi-aset-' . now()->format('Ymd-His') . '.' . ($format === 'xlsx' ? 'xlsx' : 'csv')
        );
    }

    public function create()
    {
        $formOptions = $this->formOptions();

        return view('transaksi.mutasi_aset.create', $formOptions);
    }

    public function store(StoreMutasiAsetRequest $request)
    {
        try {
            $userId = auth()->id();
            $mutasi = $this->mutasiService->create($request->validated(), $userId);

            return redirect()
                ->route('transaksi.mutasi_aset.show', $mutasi->id)
                ->with('success', 'Mutasi aset berhasil dibuat');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat mutasi aset: ' . $e->getMessage());
        }
    }

    public function show(TransaksiMutasiAset $mutasiAset)
    {
        $mutasi = $this->mutasiService->getById($mutasiAset->id);

        return view('transaksi.mutasi_aset.show', compact('mutasi'));
    }

    public function edit(TransaksiMutasiAset $mutasiAset)
    {
        if (!$mutasiAset->canEdit()) {
            return back()->with('error', 'Transaksi tidak bisa diubah pada status ini');
        }

        $formOptions = $this->formOptions();

        return view('transaksi.mutasi_aset.edit', array_merge(['mutasi' => $mutasiAset], $formOptions));
    }

    public function update(UpdateMutasiAsetRequest $request, TransaksiMutasiAset $mutasiAset)
    {
        try {
            $userId = auth()->id();
            $mutasi = $this->mutasiService->update($mutasiAset->id, $request->validated(), $userId);

            return redirect()
                ->route('transaksi.mutasi_aset.show', $mutasi->id)
                ->with('success', 'Mutasi aset berhasil diperbarui');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui mutasi aset: ' . $e->getMessage());
        }
    }

    public function destroy(TransaksiMutasiAset $mutasiAset)
    {
        try {
            $userId = auth()->id();
            $this->mutasiService->delete($mutasiAset->id, $userId);

            return redirect()
                ->route('transaksi.mutasi_aset.index')
                ->with('success', 'Mutasi aset berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus mutasi aset: ' . $e->getMessage());
        }
    }

    public function approve(TransaksiMutasiAset $mutasiAset)
    {
        try {
            $userId = auth()->id();
            $catatan = request()->input('catatan_approval', '');
            $this->mutasiService->approve($mutasiAset->id, $userId, $catatan);

            return back()->with('success', 'Mutasi aset berhasil disetujui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyetujui mutasi aset: ' . $e->getMessage());
        }
    }

    public function reject(TransaksiMutasiAset $mutasiAset)
    {
        try {
            $userId = auth()->id();
            $catatan = request()->input('catatan_approval', '');
            $this->mutasiService->reject($mutasiAset->id, $userId, $catatan);

            return back()->with('success', 'Mutasi aset berhasil ditolak');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak mutasi aset: ' . $e->getMessage());
        }
    }

    public function process(TransaksiMutasiAset $mutasiAset)
    {
        try {
            $userId = auth()->id();
            $this->mutasiService->startProcess($mutasiAset->id, $userId);

            return back()->with('success', 'Proses mutasi aset dimulai');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memulai proses mutasi aset: ' . $e->getMessage());
        }
    }

    public function completeForm(TransaksiMutasiAset $mutasiAset)
    {
        if ($mutasiAset->status !== 'proses') {
            return back()->with('error', 'Hanya mutasi dengan status proses yang bisa diselesaikan');
        }

        return view('transaksi.mutasi_aset.complete', compact('mutasiAset'));
    }

    public function complete(CompleteMutasiAsetRequest $request, TransaksiMutasiAset $mutasiAset)
    {
        try {
            $userId = auth()->id();
            $mutasi = $this->mutasiService->complete($mutasiAset->id, $request->validated(), $userId);

            return redirect()
                ->route('transaksi.mutasi_aset.show', $mutasi->id)
                ->with('success', 'Mutasi aset berhasil diselesaikan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyelesaikan mutasi aset: ' . $e->getMessage());
        }
    }

    private function formOptions(): array
    {
        $asets = DataAsetKolektif::query()
            ->where('is_active', true)
            ->orderBy('nama_aset')
            ->get(['id', 'kode_aset', 'nama_aset']);

        return [
            'asets' => $asets,
            'lokasis' => $this->masterDataService->getActiveLokasis(),
            'kondisis' => $this->masterDataService->getActiveKondisis(),
        ];
    }
}
