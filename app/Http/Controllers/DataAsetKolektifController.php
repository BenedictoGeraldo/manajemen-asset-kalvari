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
        $filters = [
            'search' => $request->input('search'),
            'per_page' => $request->input('per_page', 10),
            'department_id' => $request->input('department_id'),
            'sub_department_id' => $request->input('sub_department_id')
        ];

        $asets = $this->dataAsetService->getPaginatedAsets($filters);
        $search = $filters['search'];
        $perPage = $filters['per_page'];
        $departmentId = $filters['department_id'];
        $subDepartmentId = $filters['sub_department_id'];

        $departments = $this->masterDataService->getTopLevelDepartments();
        $subDepartments = $departmentId ? $this->masterDataService->getSubDepartments((int)$departmentId) : collect();

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
        $aset = $this->dataAsetService->getAsetById($id);
        return view('data-aset.show', compact('aset'));
    }

    public function create()
    {
        $kategoris = $this->masterDataService->getActiveKategoris();
        $lokasis = $this->masterDataService->getActiveLokasis();
        $kondisis = $this->masterDataService->getActiveKondisis();
        $pengelolas = $this->masterDataService->getActivePengelolas();
        $departments = $this->masterDataService->getTopLevelDepartments();

        return view('data-aset.create', compact('kategoris', 'lokasis', 'kondisis', 'pengelolas', 'departments'));
    }

    public function store(StoreDataAsetRequest $request)
    {
        $data = $request->validated();
        unset($data['gambar_aset']);

        if ($request->hasFile('gambar_aset')) {
            $data['gambar_aset_base64'] = $this->convertImageToBase64($request->file('gambar_aset'));
        }

        $this->dataAsetService->createAset($data);

        return redirect()->route('data-aset.index')
            ->with('success', 'Data aset berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $aset = $this->dataAsetService->getAsetById($id);
        $kategoris = $this->masterDataService->getActiveKategoris();
        $lokasis = $this->masterDataService->getActiveLokasis();
        $kondisis = $this->masterDataService->getActiveKondisis();
        $pengelolas = $this->masterDataService->getActivePengelolas();
        $departments = $this->masterDataService->getTopLevelDepartments();
        
        $currentDepartmentId = null;
        $currentSubDepartmentId = null;
        
        if ($aset->department_id) {
            $dept = \App\Models\Department::find($aset->department_id);
            if ($dept->parent_id) {
                $currentDepartmentId = $dept->parent_id;
                $currentSubDepartmentId = $dept->id;
            } else {
                $currentDepartmentId = $dept->id;
            }
        }
        
        $subDepartments = $currentDepartmentId ? $this->masterDataService->getSubDepartments($currentDepartmentId) : collect();

        return view('data-aset.edit', compact(
            'aset', 'kategoris', 'lokasis', 'kondisis', 'pengelolas', 
            'departments', 'subDepartments', 'currentDepartmentId', 'currentSubDepartmentId'
        ));
    }

    public function update(UpdateDataAsetRequest $request, string $id)
    {
        $aset = $this->dataAsetService->getAsetById($id);
        $data = $request->validated();
        unset($data['gambar_aset']);
        $hapusGambarAset = (bool) ($request->input('hapus_gambar_aset', false));
        unset($data['hapus_gambar_aset']);

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
        $this->dataAsetService->deleteAset($id);

        return redirect()->route('data-aset.index')
            ->with('success', 'Data aset berhasil dihapus!');
    }

    public function getSubDepartments(Request $request)
    {
        $parentId = $request->input('parent_id');
        if (!$parentId) {
            return response()->json([]);
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
}
