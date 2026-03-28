<?php

namespace App\Http\Controllers;

use App\Exports\TransaksiPeminjamanExport;
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
use Maatwebsite\Excel\Facades\Excel;

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

        return view('transaksi.peminjaman.show', compact('peminjaman'));
    }

    public function edit(string $id): View|RedirectResponse
    {
        $peminjaman = $this->peminjamanService->getById((int) $id);

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

        if ($peminjaman->status !== 'disetujui') {
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

        if (!in_array($peminjaman->status, ['dipinjam', 'terlambat'], true)) {
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
        try {
            $this->peminjamanService->returnAssets((int) $id, $request->validated(), (int) auth()->id());
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('transaksi.peminjaman.show', $id)
            ->with('success', 'Pengembalian aset berhasil diproses.');
    }

    public function export(string $format)
    {
        $timestamp = now()->format('Y-m-d_His');
        $filename = "transaksi-peminjaman_{$timestamp}.{$format}";

        if ($format === 'csv') {
            return Excel::download(new TransaksiPeminjamanExport(), $filename, \Maatwebsite\Excel\Excel::CSV);
        }

        return Excel::download(new TransaksiPeminjamanExport(), $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    private function formOptions(): array
    {
        $asets = DataAsetKolektif::query()
            ->where('is_active', true)
            ->orderBy('nama_aset')
            ->get(['id', 'kode_aset', 'nama_aset', 'jumlah_barang']);

        return [
            'asets' => $asets,
        ];
    }
}
