<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompletePemeliharaanRequest;
use App\Http\Requests\StorePemeliharaanRequest;
use App\Http\Requests\UpdatePemeliharaanRequest;
use App\Models\DataAsetKolektif;
use App\Services\MasterDataService;
use App\Services\PemeliharaanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PemeliharaanController extends Controller
{
    public function __construct(
        protected PemeliharaanService $pemeliharaanService,
        protected MasterDataService $masterDataService
    ) {
    }

    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'jenis' => $request->input('jenis'),
            'per_page' => $request->input('per_page', 10),
        ];

        $user = auth()->user();

        if ($user->is_super_admin) {
            // no filter � lihat semua record
        } elseif ($user->isAdminDivisi()) {
            $filters['department_id'] = $user->department_id;
        } else {
            $filters['creator_id'] = $user->id;
        }

        $pemeliharaans = $this->pemeliharaanService->getPaginatedPemeliharaan($filters);

        return view('transaksi.pemeliharaan.index', [
            'pemeliharaans' => $pemeliharaans,
            'search' => $filters['search'],
            'status' => $filters['status'],
            'jenis' => $filters['jenis'],
            'perPage' => $filters['per_page'],
        ]);
    }

    public function create(): View
    {
        return view('transaksi.pemeliharaan.create', $this->formOptions());
    }

    public function store(StorePemeliharaanRequest $request): RedirectResponse
    {
        try {
            $this->pemeliharaanService->create($request->validated(), auth()->id());
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('transaksi.pemeliharaan.index')
            ->with('success', 'Transaksi pemeliharaan berhasil ditambahkan.');
    }

    public function show(string $id): View
    {
        $pemeliharaan = $this->pemeliharaanService->getById((int) $id);
        $this->authorizeAccess($pemeliharaan);

        return view('transaksi.pemeliharaan.show', compact('pemeliharaan'));
    }

    public function edit(string $id): View|RedirectResponse
    {
        $pemeliharaan = $this->pemeliharaanService->getById((int) $id);
        $this->authorizeAccess($pemeliharaan);

        if (!$pemeliharaan->canEdit()) {
            return redirect()->route('transaksi.pemeliharaan.show', $pemeliharaan->id)
                ->with('error', 'Transaksi pada status saat ini tidak dapat diubah.');
        }

        return view('transaksi.pemeliharaan.edit', [
            'pemeliharaan' => $pemeliharaan,
            ...$this->formOptions(),
        ]);
    }

    public function update(UpdatePemeliharaanRequest $request, string $id): RedirectResponse
    {
        $pemeliharaan = $this->pemeliharaanService->getById((int) $id);
        $this->authorizeAccess($pemeliharaan);

        try {
            $this->pemeliharaanService->update((int) $id, $request->validated(), auth()->id());
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('transaksi.pemeliharaan.index')
            ->with('success', 'Transaksi pemeliharaan berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $pemeliharaan = $this->pemeliharaanService->getById((int) $id);
        $this->authorizeAccess($pemeliharaan);

        try {
            $this->pemeliharaanService->delete((int) $id, auth()->id());
        } catch (\Throwable $e) {
            return redirect()->route('transaksi.pemeliharaan.index')->with('error', $e->getMessage());
        }

        return redirect()->route('transaksi.pemeliharaan.index')
            ->with('success', 'Transaksi pemeliharaan berhasil dihapus.');
    }

    public function approve(Request $request, string $id): RedirectResponse
    {
        $this->requireAdmin();
        $pemeliharaan = $this->pemeliharaanService->getById((int) $id);
        $this->authorizeAccess($pemeliharaan);

        try {
            $this->pemeliharaanService->approve((int) $id, (int) auth()->id(), $request->input('catatan_approval'));
        } catch (\Throwable $e) {
            return redirect()->route('transaksi.pemeliharaan.show', $id)
                ->with('error', 'Gagal menyetujui pemeliharaan: ' . $e->getMessage());
        }

        return redirect()->route('transaksi.pemeliharaan.show', $id)
            ->with('success', 'Pemeliharaan berhasil disetujui.');
    }

    public function reject(Request $request, string $id): RedirectResponse
    {
        $this->requireAdmin();
        $pemeliharaan = $this->pemeliharaanService->getById((int) $id);
        $this->authorizeAccess($pemeliharaan);

        try {
            $this->pemeliharaanService->reject((int) $id, (int) auth()->id(), $request->input('catatan_approval'));
        } catch (\Throwable $e) {
            return redirect()->route('transaksi.pemeliharaan.show', $id)
                ->with('error', 'Gagal menolak pemeliharaan: ' . $e->getMessage());
        }

        return redirect()->route('transaksi.pemeliharaan.show', $id)
            ->with('success', 'Pemeliharaan berhasil ditolak.');
    }

    public function startProcess(string $id): RedirectResponse
    {
        $this->requireAdmin();
        $pemeliharaan = $this->pemeliharaanService->getById((int) $id);
        $this->authorizeAccess($pemeliharaan);

        try {
            $this->pemeliharaanService->startProcess((int) $id, (int) auth()->id());
        } catch (\Throwable $e) {
            return redirect()->route('transaksi.pemeliharaan.show', $id)
                ->with('error', 'Gagal memulai proses pemeliharaan: ' . $e->getMessage());
        }

        return redirect()->route('transaksi.pemeliharaan.show', $id)
            ->with('success', 'Pemeliharaan masuk tahap proses.');
    }

    public function completeForm(string $id): View|RedirectResponse
    {
        $pemeliharaan = $this->pemeliharaanService->getById((int) $id);
        $this->requireAdmin();
        $this->authorizeAccess($pemeliharaan);

        if ($pemeliharaan->status !== 'proses') {
            return redirect()->route('transaksi.pemeliharaan.show', $id)
                ->with('error', 'Penyelesaian hanya dapat dilakukan pada status proses.');
        }

        return view('transaksi.pemeliharaan.complete', [
            'pemeliharaan' => $pemeliharaan,
            'kondisis' => $this->masterDataService->getActiveKondisis(),
        ]);
    }

    public function complete(CompletePemeliharaanRequest $request, string $id): RedirectResponse
    {
        $this->requireAdmin();
        $pemeliharaan = $this->pemeliharaanService->getById((int) $id);
        $this->authorizeAccess($pemeliharaan);

        try {
            $this->pemeliharaanService->complete((int) $id, $request->validated(), (int) auth()->id());
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('transaksi.pemeliharaan.show', $id)
            ->with('success', 'Pemeliharaan berhasil diselesaikan dan kondisi aset diperbarui.');
    }

    private function canManageAll(): bool
    {
        return auth()->user()->is_super_admin
            || auth()->user()->hasPermission('transaksi.pemeliharaan.approve');
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
        } elseif ($record->created_by === $user->id) {
            return;
        }

        abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
    }

    private function formOptions(): array
    {
        $asets = DataAsetKolektif::query()
            ->where('is_active', true)
            ->orderBy('nama_aset')
            ->get(['id', 'kode_aset', 'nama_aset', 'kondisi_id']);

        return [
            'asets' => $asets,
            'kondisis' => $this->masterDataService->getActiveKondisis(),
        ];
    }
}
