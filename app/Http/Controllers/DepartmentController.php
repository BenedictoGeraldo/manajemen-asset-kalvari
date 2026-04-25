<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('parent')->orderBy('name')->get();
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        $parents = Department::orderBy('name')->get();
        return view('departments.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code',
            'type' => 'nullable|string|max:50',
            'parent_id' => 'nullable|exists:departments,id',
        ]);

        Department::create([
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'parent_id' => $request->parent_id,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('departments.index')
            ->with('success', 'Departemen berhasil ditambahkan!');
    }

    public function edit(Department $department)
    {
        $parents = Department::where('id', '!=', $department->id)->orderBy('name')->get();
        return view('departments.edit', compact('department', 'parents'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code,' . $department->id,
            'type' => 'nullable|string|max:50',
            'parent_id' => 'nullable|exists:departments,id',
        ]);

        $department->update([
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'parent_id' => $request->parent_id,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('departments.index')
            ->with('success', 'Departemen berhasil diupdate!');
    }

    public function destroy(Department $department)
    {
        if ($department->children()->count() > 0) {
            return redirect()->route('departments.index')
                ->with('error', 'Tidak dapat menghapus departemen yang memiliki sub-departemen!');
        }

        if ($department->users()->count() > 0) {
            return redirect()->route('departments.index')
                ->with('error', 'Tidak dapat menghapus departemen yang masih memiliki user!');
        }

        $department->update(['deleted_by' => auth()->id()]);
        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Departemen berhasil dihapus!');
    }
}
