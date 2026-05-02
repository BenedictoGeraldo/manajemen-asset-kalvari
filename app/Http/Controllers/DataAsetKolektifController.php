<?php

namespace App\Http\Controllers;

use App\Services\DataAsetService;
use App\Services\MasterDataService;
use App\Http\Requests\StoreDataAsetRequest;
use App\Http\Requests\UpdateDataAsetRequest;
use App\Exports\DataAsetExport;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Department;

class DataAsetKolektifController extends Controller
{
    protected $dataAsetService;
    protected $masterDataService;

    public function __construct(DataAsetService $dataAsetService, MasterDataService $masterDataService)
    {
        $this->dataAsetService = $dataAsetService;
        $this->masterDataService = $masterDataService;
    }

    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $allowedDepartmentIds = null;
        $allowedTopDepartments = null;

        if ($user && $user->isAdminDivisi()) {
            // Admin divisi boleh melihat aset departemennya dan sub-departemen di bawahnya
            $allowedDepartmentIds = [];
            if ($user->department_id) {
                $allowedDepartmentIds = \App\Models\Department::where('id', $user->department_id)
                    ->orWhere('parent_id', $user->department_id)
                    ->pluck('id')
                    ->map(fn($id) => (int) $id)
                    ->toArray();
            }

            // Untuk filter dropdown "Departemen", tampilkan Bidang (Level 1)
            $dept = $user->department;
            if ($dept) {
                $parentDept = $dept->parent;
                if ($parentDept && $parentDept->parent_id === null) {
                    // $dept is Level 1
                    $allowedTopDepartments = collect([$dept]);
                } elseif ($parentDept) {
                    // $dept is Level 2, $parentDept is Level 1
                    $allowedTopDepartments = collect([$parentDept]);
                } else {
                    // $dept is Root
                    $allowedTopDepartments = collect([$dept]);
                }
            } else {
                $allowedTopDepartments = collect();
            }
        }

        $filters = [
            'search' => $request->input('search'),
            'per_page' => $request->input('per_page', 10),
            'department_id' => $request->input('department_id'),
            'sub_department_id' => $request->input('sub_department_id')
        ];

        if (is_array($allowedDepartmentIds)) {
            $filters['allowed_department_ids'] = $allowedDepartmentIds;

            // Hardening: kalau user admin-divisi coba filter departemen orang lain, abaikan.
            if (!empty($filters['department_id']) && !in_array((int) $filters['department_id'], $allowedDepartmentIds, true)) {
                $filters['department_id'] = null;
            }
            if (!empty($filters['sub_department_id']) && !in_array((int) $filters['sub_department_id'], $allowedDepartmentIds, true)) {
                $filters['sub_department_id'] = null;
            }
        }

        $asets = $this->dataAsetService->getPaginatedAsets($filters);
        $search = $filters['search'];
        $perPage = $filters['per_page'];
        $departmentId = $filters['department_id'];
        $subDepartmentId = $filters['sub_department_id'];

        $departments = $allowedTopDepartments ?? $this->masterDataService->getTopLevelDepartments();
        $subDepartments = $departmentId ? $this->masterDataService->getSubDepartments((int)$departmentId) : collect();

        // Hardening & UX: filter dropdown sub_department agar hanya memunculkan yang diizinkan
        if (is_array($allowedDepartmentIds)) {
            $subDepartments = $subDepartments->filter(fn($sub) => in_array($sub->id, $allowedDepartmentIds, true));
        }

        if ($request->header('X-Partial-Request') === 'table') {
            return view('data-aset.partials.table', compact('asets'))->render();
        }

        return view('data-aset.index', compact(
            'asets', 'search', 'perPage', 
            'departments', 'subDepartments', 
            'departmentId', 'subDepartmentId'
        ));
    }

    public function show(string $id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $aset = $this->dataAsetService->getAsetById($id);

        if ($user && $user->isAdminDivisi() && !$this->canAccessDepartmentId($user, (int) $aset->department_id)) {
            abort(403, 'Anda tidak memiliki akses ke aset ini.');
        }

        return view('data-aset.show', compact('aset'));
    }

    public function create()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $kategoris = $this->masterDataService->getActiveKategoris();
        $lokasis = $this->masterDataService->getActiveLokasis();
        $kondisis = $this->masterDataService->getActiveKondisis();
        $pengelolas = $this->masterDataService->getActivePengelolas();
        $departments = $this->masterDataService->getDepartmentOptions();

        if ($user && $user->isAdminDivisi()) {
            $dept = $user->department;
            if ($dept) {
                $dept->level = 0;
                $departments = [$dept];
            } else {
                $departments = [];
            }
        }

        return view('data-aset.create', compact('kategoris', 'lokasis', 'kondisis', 'pengelolas', 'departments'));
    }

    public function store(StoreDataAsetRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $data = $request->validated();
        unset($data['gambar_aset']);

        if ($user && $user->isAdminDivisi() && !$this->canAccessDepartmentId($user, (int) $data['department_id'])) {
            abort(403, 'Anda tidak memiliki akses untuk membuat aset di departemen tersebut.');
        }

        if ($request->hasFile('gambar_aset')) {
            $data['gambar_aset_base64'] = $this->convertImageToBase64($request->file('gambar_aset'));
        }

        $this->dataAsetService->createAset($data);

        return redirect()->route('data-aset.index')
            ->with('success', 'Data aset berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $aset = $this->dataAsetService->getAsetById($id);

        if ($user && $user->isAdminDivisi() && !$this->canAccessDepartmentId($user, (int) $aset->department_id)) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah aset ini.');
        }

        $kategoris = $this->masterDataService->getActiveKategoris();
        $lokasis = $this->masterDataService->getActiveLokasis();
        $kondisis = $this->masterDataService->getActiveKondisis();
        $pengelolas = $this->masterDataService->getActivePengelolas();
        $departments = $this->masterDataService->getDepartmentOptions();

        if ($user && $user->isAdminDivisi()) {
            $dept = $user->department;
            if ($dept) {
                $dept->level = 0;
                $departments = [$dept];
            } else {
                $departments = [];
            }
        }

        return view('data-aset.edit', compact(
            'aset', 'kategoris', 'lokasis', 'kondisis', 'pengelolas', 
            'departments'
        ));
    }

    public function update(UpdateDataAsetRequest $request, string $id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $aset = $this->dataAsetService->getAsetById($id);

        if ($user && $user->isAdminDivisi() && !$this->canAccessDepartmentId($user, (int) $aset->department_id)) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah aset ini.');
        }

        $data = $request->validated();
        unset($data['gambar_aset']);
        $hapusGambarAset = (bool) ($request->input('hapus_gambar_aset', false));
        unset($data['hapus_gambar_aset']);

        if ($user && $user->isAdminDivisi() && !$this->canAccessDepartmentId($user, (int) $data['department_id'])) {
            abort(403, 'Anda tidak memiliki akses untuk memindahkan aset ke departemen tersebut.');
        }

        if ($aset->gambar_aset_base64 && !$hapusGambarAset && $request->hasFile('gambar_aset')) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'gambar_aset' => 'Gambar lama masih ada. Hapus gambar aset terlebih dahulu sebelum menambahkan gambar baru.'
                ]);
        }

        if ($hapusGambarAset) {
            $data['gambar_aset_base64'] = null;
        }

        if ($request->hasFile('gambar_aset')) {
            $data['gambar_aset_base64'] = $this->convertImageToBase64($request->file('gambar_aset'));
        }

        $this->dataAsetService->updateAset($id, $data);

        return redirect()->route('data-aset.index')
            ->with('success', 'Data aset berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $aset = $this->dataAsetService->getAsetById($id);

        if ($user && $user->isAdminDivisi() && !$this->canAccessDepartmentId($user, (int) $aset->department_id)) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus aset ini.');
        }

        $this->dataAsetService->deleteAset($id);

        return redirect()->route('data-aset.index')
            ->with('success', 'Data aset berhasil dihapus!');
    }

    public function getSubDepartments(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $parentId = $request->input('parent_id');
        if (!$parentId) {
            return response()->json([]);
        }

        if ($user && $user->isAdminDivisi()) {
            $allowedDepartmentIds = [];
            if ($user->department_id) {
                $allowedDepartmentIds = \App\Models\Department::where('id', $user->department_id)
                    ->orWhere('parent_id', $user->department_id)
                    ->pluck('id')
                    ->map(fn($id) => (int) $id)
                    ->toArray();
            }

            // Kalau parentId yang direquest tidak ada di allowed, langsung kosong
            $parentDept = \App\Models\Department::find($parentId);
            $rootDept = $user->department ? ($user->department->parent_id === null ? $user->department : $user->department->parent) : null;

            if (!$rootDept || (int) $parentId !== (int) $rootDept->id) {
                return response()->json([]);
            }

            $subDepartments = $this->masterDataService->getSubDepartments($parentId);
            $subDepartments = $subDepartments->filter(fn($sub) => in_array($sub->id, $allowedDepartmentIds, true))->values();
            return response()->json($subDepartments);
        }

        $subDepartments = $this->masterDataService->getSubDepartments($parentId);
        return response()->json($subDepartments);
    }

    public function export($format)
    {
        $timestamp = now()->format('Y-m-d_His');
        $filename = "data-aset_{$timestamp}.{$format}";

        if ($format === 'csv') {
            return Excel::download(new DataAsetExport, $filename, \Maatwebsite\Excel\Excel::CSV);
        }

        return Excel::download(new DataAsetExport, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function printLabel(string $id)
    {
        $aset = $this->dataAsetService->getAsetById($id);
        return view('data-aset.label', compact('aset'));
    }

    private function convertImageToBase64(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType() ?: 'image/jpeg';
        $encoded = base64_encode(file_get_contents($file->getRealPath()));

        return "data:{$mimeType};base64,{$encoded}";
    }

    private function canAccessDepartmentId(\App\Models\User $user, int $departmentId): bool
    {
        if ($user->is_super_admin) {
            return true;
        }

        if (!$user->isAdminDivisi()) {
            return true;
        }

        // Admin divisi boleh akses departemennya dan sub-departemen di bawahnya
        if (!$user->department_id) return false;

        if ((int) $user->department_id === (int) $departmentId) {
            return true;
        }

        // Check if the target department is a child of the user's department
        $targetDept = \App\Models\Department::find($departmentId);
        if ($targetDept && (int) $targetDept->parent_id === (int) $user->department_id) {
            return true;
        }

        return false;
    }
}
