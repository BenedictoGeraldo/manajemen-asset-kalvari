<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMutasiAsetRequest;
use App\Http\Requests\UpdateMutasiAsetRequest;
use App\Http\Requests\CompleteMutasiAsetRequest;
use App\Models\TransaksiMutasiAset;
use App\Models\DataAsetKolektif;
use App\Services\MutasiAsetService;
use App\Services\MasterDataService;

class MutasiAsetController extends Controller
{
    public function __construct(
        private MutasiAsetService $mutasiService,
        private MasterDataService $masterDataService,
    ) {}

    public function index()
    {
        $filters = request()->only(['search', 'status', 'jenis', 'per_page']);

        $user = auth()->user();

        if ($user->is_super_admin) {
            // no filter — lihat semua
        } elseif ($user->isAdminDivisi()) {
            $filters['department_id'] = $user->department_id;
        } else {
            $filters['creator_id'] = $user->id;
        }

        $mutasis = $this->mutasiService->getPaginatedMutasi($filters);

        return view('transaksi.mutasi-aset.index', compact('mutasis', 'filters'));
    }

    public function create()
    {
        $formOptions = $this->formOptions();

        return view('transaksi.mutasi-aset.create', $formOptions);
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
        $this->authorizeAccess($mutasi);

        return view('transaksi.mutasi-aset.show', compact('mutasi'));
    }

    public function edit(TransaksiMutasiAset $mutasiAset)
    {
        $this->authorizeAccess($mutasiAset);

        if (!$mutasiAset->canEdit()) {
            return back()->with('error', 'Transaksi tidak bisa diubah pada status ini');
        }

        $formOptions = $this->formOptions();

        return view('transaksi.mutasi-aset.edit', array_merge(['mutasi' => $mutasiAset], $formOptions));
    }

    public function update(UpdateMutasiAsetRequest $request, TransaksiMutasiAset $mutasiAset)
    {
        $this->authorizeAccess($mutasiAset);

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
        $this->authorizeAccess($mutasiAset);

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
        $this->requireAdmin();

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
        $this->requireAdmin();

        try {
            $userId = auth()->id();
            $catatan = request()->input('catatan_approval', '');
            $this->mutasiService->reject($mutasiAset->id, $userId, $catatan);

            return back()->with('success', 'Mutasi aset berhasil ditolak');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak mutasi aset: ' . $e->getMessage());
        }
    }

    public function completeForm(TransaksiMutasiAset $mutasiAset)
    {
        $this->requireAdmin();

        if ($mutasiAset->status !== 'disetujui') {
            return back()->with('error', 'Hanya mutasi dengan status disetujui yang bisa diselesaikan');
        }

        return view('transaksi.mutasi-aset.complete', compact('mutasiAset'));
    }

    public function complete(CompleteMutasiAsetRequest $request, TransaksiMutasiAset $mutasiAset)
    {
        $this->requireAdmin();

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

    private function canManageAll(): bool
    {
        return auth()->user()->is_super_admin
            || auth()->user()->hasPermission('transaksi.mutasi_aset.approve');
    }

    private function requireAdmin(): void
    {
        if (!$this->canManageAll()) {
            abort(403, 'Anda tidak memiliki akses untuk aksi ini.');
        }
    }

    private function authorizeAccess($record): void
    {
        $user = auth()->user();

        if ($user->is_super_admin) {
            return;
        }

        if ($user->isAdminDivisi()) {
            if ($record->creator && $record->creator->department_id === $user->department_id) {
                return;
            }
        } elseif ((int) $record->created_by === (int) $user->id) {
            return;
        }

        abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
    }

    private function formOptions(): array
    {
        $asets = DataAsetKolektif::query()
            ->where('is_active', true)
            ->orderBy('nama_aset')
            ->get(['id', 'kode_aset', 'nama_aset']);

        $lokasis = $this->masterDataService->getActiveLokasis();
        $lokasiGrouped = $lokasis->groupBy('nama_lokasi')->map(fn($items, $nama) => [
            'group' => $nama,
            'items' => $items->map(fn($x) => [
                'id' => $x->id,
                'label' => $x->sub_lokasi ?: $nama,
            ])->values()->toArray(),
        ])->values()->toArray();

        return [
            'asets' => $asets,
            'lokasiGrouped' => $lokasiGrouped,
        ];
    }
}
