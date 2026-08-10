<?php

namespace App\Http\Controllers;

use App\Http\Requests\RejectPembelianRequest;
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
        $user = auth()->user();

        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'per_page' => $request->input('per_page', 10),
        ];

        if (!$user->is_super_admin) {
            $allowedDepartmentIds = $this->getAllowedDepartmentIds($user);
            $filters['allowed_department_ids'] = $allowedDepartmentIds;
        }

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

        $this->authorizeAccess($pembelian);

        return view('transaksi.pembelian.show', compact('pembelian'));
    }

    public function edit(string $id)
    {
        $pembelian = $this->pembelianService->getById((int) $id);

        $this->authorizeAccess($pembelian);

        if (!$this->canEditDelete($pembelian)) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah transaksi ini.');
        }

        if ($pembelian->status === 'disetujui' || $pembelian->status === 'ditolak') {
            return redirect()->route('transaksi.pembelian.show', $pembelian->id)
                ->with('error', 'Transaksi yang sudah ' . $pembelian->status . ' tidak dapat diubah.');
        }

        $options = $this->masterDataOptions();

        foreach ($pembelian->items as $item) {
            if ($item->kategori_id && !$options['kategoris']->contains('id', $item->kategori_id)) {
                $inactive = \App\Models\MasterKategori::find($item->kategori_id);
                if ($inactive) $options['kategoris']->push($inactive);
            }
            if ($item->lokasi_id && !$options['lokasis']->contains('id', $item->lokasi_id)) {
                $inactive = \App\Models\MasterLokasi::find($item->lokasi_id);
                if ($inactive) $options['lokasis']->push($inactive);
            }
            if ($item->kondisi_id && !$options['kondisis']->contains('id', $item->kondisi_id)) {
                $inactive = \App\Models\MasterKondisi::find($item->kondisi_id);
                if ($inactive) $options['kondisis']->push($inactive);
            }
            if ($item->pengelola_id && !$options['pengelolas']->contains('id', $item->pengelola_id)) {
                $inactive = \App\Models\MasterPengelola::find($item->pengelola_id);
                if ($inactive) $options['pengelolas']->push($inactive);
            }
        }

        return view('transaksi.pembelian.edit', ['pembelian' => $pembelian, ...$options]);
    }

    public function update(UpdatePembelianRequest $request, string $id)
    {
        $pembelian = $this->pembelianService->getById((int) $id);

        $this->authorizeAccess($pembelian);

        if (!$this->canEditDelete($pembelian)) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah transaksi ini.');
        }

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
        $pembelian = $this->pembelianService->getById((int) $id);

        $this->authorizeAccess($pembelian);

        if (!$this->canEditDelete($pembelian)) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus transaksi ini.');
        }

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
        $pembelian = $this->pembelianService->getById((int) $id);

        if (!$this->canApproveReject($pembelian)) {
            abort(403, 'Anda tidak memiliki akses untuk menyetujui transaksi ini.');
        }

        try {
            $this->pembelianService->approveAndPostToAset((int) $id, (int) auth()->id());
        } catch (\Throwable $e) {
            return redirect()->route('transaksi.pembelian.show', $id)
                ->with('error', 'Gagal menyetujui pembelian: ' . $e->getMessage());
        }

        return redirect()->route('transaksi.pembelian.show', $id)
            ->with('success', 'Pembelian disetujui dan berhasil diposting ke Data Aset.');
    }

    public function reject(RejectPembelianRequest $request, string $id)
    {
        $pembelian = $this->pembelianService->getById((int) $id);

        if (!$this->canApproveReject($pembelian)) {
            abort(403, 'Anda tidak memiliki akses untuk menolak transaksi ini.');
        }

        try {
            $this->pembelianService->reject((int) $id, $request->validated()['alasan_penolakan'], (int) auth()->id());
        } catch (\Throwable $e) {
            return redirect()->route('transaksi.pembelian.show', $id)
                ->with('error', 'Gagal menolak pembelian: ' . $e->getMessage());
        }

        return redirect()->route('transaksi.pembelian.show', $id)
            ->with('success', 'Pembelian telah ditolak.');
    }

    private function authorizeAccess($pembelian): void
    {
        $user = auth()->user();

        if ($user->is_super_admin) {
            return;
        }

        $allowedDepartmentIds = $this->getAllowedDepartmentIds($user);

        if (!in_array($pembelian->department_id, $allowedDepartmentIds)) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }
    }

    private function canEditDelete($pembelian): bool
    {
        $user = auth()->user();

        if ($user->is_super_admin) {
            return true;
        }

        return $user->hasPermission('transaksi.pembelian.edit');
    }

    private function canApproveReject($pembelian): bool
    {
        $user = auth()->user();

        if ($user->is_super_admin) {
            return true;
        }

        if (!$user->hasPermission('transaksi.pembelian.approve')) {
            return false;
        }

        $allowedDepartmentIds = $this->getAllowedDepartmentIds($user);

        return in_array($pembelian->department_id, $allowedDepartmentIds);
    }

    private function getAllowedDepartmentIds($user): array
    {
        if (!$user->department_id) {
            return [];
        }

        return \App\Models\Department::where('id', $user->department_id)
            ->orWhere('parent_id', $user->department_id)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->toArray();
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
