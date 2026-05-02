<?php

namespace App\Services;

use App\Models\MasterKategori;
use App\Models\MasterLokasi;
use App\Models\MasterKondisi;
use App\Models\MasterPengelola;
use App\Models\Department;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class MasterDataService
{
    /**
     * Get active categories with caching
     *
     * @return Collection
     */
    public function getActiveKategoris(): Collection
    {
        return Cache::remember('active_kategoris', 3600, function () {
            return MasterKategori::active()->orderBy('nama_kategori')->get();
        });
    }

    /**
     * Get active locations with caching
     *
     * @return Collection
     */
    public function getActiveLokasis(): Collection
    {
        return Cache::remember('active_lokasis', 3600, function () {
            return MasterLokasi::active()->orderBy('kode_lokasi')->get();
        });
    }

    /**
     * Get active conditions with caching
     *
     * @return Collection
     */
    public function getActiveKondisis(): Collection
    {
        return Cache::remember('active_kondisis', 3600, function () {
            return MasterKondisi::active()->ordered()->get();
        });
    }

    /**
     * Get active administrators with caching
     *
     * @return Collection
     */
    public function getActivePengelolas(): Collection
    {
        return Cache::remember('active_pengelolas', 3600, function () {
            return MasterPengelola::active()->orderBy('nama_pengelola')->get();
        });
    }

    /**
     * Get top level departments
     *
     * @return Collection
     */
    public function getTopLevelDepartments(): Collection
    {
        return Cache::remember('top_level_departments', 3600, function () {
            $root = Department::whereNull('parent_id')->first();
            if ($root) {
                return Department::where('parent_id', $root->id)->orderBy('code')->get();
            }
            return collect();
        });
    }

    /**
     * Get sub departments by parent ID
     *
     * @param int $parentId
     * @return Collection
     */
    public function getSubDepartments(int $parentId): Collection
    {
        return Cache::remember("sub_departments_{$parentId}", 3600, function () use ($parentId) {
            return Department::where('parent_id', $parentId)->orderBy('code')->get();
        });
    }

    /**
     * Get department options as a flat list with levels for hierarchy
     *
     * @return array
     */
    public function getDepartmentOptions(): array
    {
        return Cache::remember('department_options_tree', 3600, function () {
            $allDepartments = Department::orderBy('code')->get();
            $tree = [];
            $this->buildDepartmentTree($allDepartments, null, 0, $tree);
            return $tree;
        });
    }

    /**
     * Helper to build tree recursively
     */
    private function buildDepartmentTree($departments, $parentId, $level, &$tree)
    {
        $children = $departments->where('parent_id', $parentId);
        foreach ($children as $child) {
            $child->level = $level;
            $tree[] = $child;
            $this->buildDepartmentTree($departments, $child->id, $level + 1, $tree);
        }
    }

    /**
     * Clear all master data cache
     *
     * @return void
     */
    public function clearMasterDataCache(): void
    {
        Cache::forget('active_kategoris');
        Cache::forget('active_lokasis');
        Cache::forget('active_kondisis');
        Cache::forget('active_pengelolas');
        Cache::forget('top_level_departments');
        Cache::forget('department_options_tree');
    }
}
