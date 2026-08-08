<?php

namespace App\Http\Controllers;

use App\Http\Requests\HandoverPeminjamanRequest;
use App\Http\Requests\ReturnPeminjamanRequest;
use App\Http\Requests\StorePeminjamanRequest;
use App\Http\Requests\UpdatePeminjamanRequest;
use App\Models\DataAsetKolektif;
use App\Services\MasterDataService;
use App\Services\PeminjamanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    public function __construct(
        protected PeminjamanService $peminjamanService,
        protected MasterDataService $masterDataService
    ) {
    }

    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'per_page' => $request->input('per_page', 10),
        ];

        $user = auth()->user();

        if ($user->is_super_admin) {
            // no filter — lihat semua record
        } elseif ($user->isAdminDivisi()) {
            $filters['department_id'] = $user->department_id;
        } else {
            $filters['creator_id'] = $user->id;
        }

        $peminjamans = $this->peminjamanService->getPaginatedPeminjaman($filters);

        return view('transaksi.peminjaman.index', [
            'peminjamans' => $peminjamans,
            'search' => $filters['search'],
            'status' => $filters['status'],
            'perPage' => $filters['per_page'],
        ]);
    }

    public function create(): View
    {
        return view('transaksi.peminjaman.create', $this->formOptions());
    }

    public function store(StorePeminjamanRequest $request): RedirectResponse
    {
        try {
            $this->peminjamanService->create($request->validated(), auth()->id());
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('transaksi.peminjaman.index')
            ->with('success', 'Transaksi peminjaman berhasil ditambahkan.');
    }

    public function show(string $id): View
    {
        $peminjaman = $this->peminjamanService->getById((int) $id);
        $this->authorizeAccess($peminjaman);

        return view('transaksi.peminjaman.show', compact('peminjaman'));
    }

    public function edit(string $id): View|RedirectResponse
    {
        $peminjaman = $this->peminjamanService->getById((int) $id);
        $this->authorizeAccess($peminjaman);

        if (!$peminjaman->canEdit()) {
            return redirect()->route('transaksi.peminjaman.show', $peminjaman->id)
                ->with('error', 'Transaksi pada status saat ini tidak dapat diubah.');
        }

        return view('transaksi.peminjaman.edit', [
            'peminjaman' => $peminjaman,
            ...$this->formOptions(),
        ]);
    }

    public function update(UpdatePeminjamanRequest $request, string $id): RedirectResponse
    {
        $peminjaman = $this->peminjamanService->getById((int) $id);
        $this->authorizeAccess($peminjaman);

        try {
            $this->peminjamanService->update((int) $id, $request->validated(), auth()->id());
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('transaksi.peminjaman.index')
            ->with('success', 'Transaksi peminjaman berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $peminjaman = $this->peminjamanService->getById((int) $id);
        $this->authorizeAccess($peminjaman);

        try {
            $this->peminjamanService->delete((int) $id, auth()->id());
        } catch (\Throwable $e) {
            return redirect()->route('transaksi.peminjaman.index')->with('error', $e->getMessage());
        }

        return redirect()->route('transaksi.peminjaman.index')
            ->with('success', 'Transaksi peminjaman berhasil dihapus.');
    }

    public function approve(Request $request, string $id): RedirectResponse
    {
        $this->requireAdmin();
        $peminjaman = $this->peminjamanService->getById((int) $id);
        $this->authorizeAccess($peminjaman);

        try {
            $this->peminjamanService->approve((int) $id, (int) auth()->id(), $request->input('catatan_approval'));
        } catch (\Throwable $e) {
            return redirect()->route('transaksi.peminjaman.show', $id)
                ->with('error', 'Gagal menyetujui peminjaman: ' . $e->getMessage());
        }

        return redirect()->route('transaksi.peminjaman.show', $id)
            ->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function reject(Request $request, string $id): RedirectResponse
    {
        $this->requireAdmin();
        $peminjaman = $this->peminjamanService->getById((int) $id);
        $this->authorizeAccess($peminjaman);

        try {
            $this->peminjamanService->reject((int) $id, (int) auth()->id(), $request->input('catatan_approval'));
        } catch (\Throwable $e) {
            return redirect()->route('transaksi.peminjaman.show', $id)
                ->with('error', 'Gagal menolak peminjaman: ' . $e->getMessage());
        }

        return redirect()->route('transaksi.peminjaman.show', $id)
            ->with('success', 'Peminjaman berhasil ditolak.');
    }

    public function handoverForm(string $id): View|RedirectResponse
    {
        $peminjaman = $this->peminjamanService->getById((int) $id);
        $this->requireAdmin();
        $this->authorizeAccess($peminjaman);

        if ($peminjaman->status !== \App\Enums\PeminjamanStatus::DISETUJUI) {
            return redirect()->route('transaksi.peminjaman.show', $id)
                ->with('error', 'Serah terima hanya dapat dilakukan pada transaksi disetujui.');
        }

        return view('transaksi.peminjaman.handover', [
            'peminjaman' => $peminjaman,
            'kondisis' => $this->masterDataService->getActiveKondisis(),
        ]);
    }

    public function handover(HandoverPeminjamanRequest $request, string $id): RedirectResponse
    {
        $this->requireAdmin();
        $peminjaman = $this->peminjamanService->getById((int) $id);
        $this->authorizeAccess($peminjaman);

        try {
            $this->peminjamanService->handover((int) $id, $request->validated(), (int) auth()->id());
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('transaksi.peminjaman.show', $id)
            ->with('success', 'Serah terima peminjaman berhasil diproses.');
    }

    public function returnForm(string $id): View|RedirectResponse
    {
        $peminjaman = $this->peminjamanService->getById((int) $id);
        $this->requireAdmin();
        $this->authorizeAccess($peminjaman);

        if (!in_array($peminjaman->status, [\App\Enums\PeminjamanStatus::DIPINJAM, \App\Enums\PeminjamanStatus::TERLAMBAT], true)) {
            return redirect()->route('transaksi.peminjaman.show', $id)
                ->with('error', 'Pengembalian hanya dapat dilakukan pada transaksi dipinjam/terlambat.');
        }

        return view('transaksi.peminjaman.return', [
            'peminjaman' => $peminjaman,
            'kondisis' => $this->masterDataService->getActiveKondisis(),
        ]);
    }

    public function returnAssets(ReturnPeminjamanRequest $request, string $id): RedirectResponse
    {
        $this->requireAdmin();
        $peminjaman = $this->peminjamanService->getById((int) $id);
        $this->authorizeAccess($peminjaman);

        try {
            $this->peminjamanService->returnAssets((int) $id, $request->validated(), (int) auth()->id());
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('transaksi.peminjaman.show', $id)
            ->with('success', 'Pengembalian aset berhasil diproses.');
    }

    private function canManageAll(): bool
    {
        return auth()->user()->is_super_admin
            || auth()->user()->hasPermission('transaksi.peminjaman.approve');
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
            ->get(['id', 'kode_aset', 'nama_aset', 'jumlah_barang']);

        $reservedMap = $this->peminjamanService->getReservedStock();

        $asets->each(function ($aset) use ($reservedMap) {
            $reserved = $reservedMap[$aset->id] ?? 0;
            $aset->reserved = $reserved;
            $aset->available = $aset->jumlah_barang - $reserved;
        });

        return [
            'asets' => $asets,
        ];
    }
}
