<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'department'])->orderBy('name')->get();
        return view('user-management.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        return view('user-management.create', compact('roles', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'is_super_admin' => ['boolean'],
            'is_active' => ['boolean'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'department_id' => ['nullable', 'exists:departments,id']
        ]);

        $is_super_admin = $request->boolean('is_super_admin');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_super_admin' => $is_super_admin,
            'is_active' => $request->boolean('is_active', true),
            'role_id' => !$is_super_admin ? $request->role_id : null,
            'department_id' => !$is_super_admin ? $request->department_id : null,
        ]);

        return redirect()->route('user-management.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('user-management.edit', compact('user', 'roles', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'is_super_admin' => ['boolean'],
            'is_active' => ['boolean'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'department_id' => ['nullable', 'exists:departments,id']
        ]);

        $is_super_admin = $request->boolean('is_super_admin');

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'is_super_admin' => $is_super_admin,
            'is_active' => $request->boolean('is_active'),
            'role_id' => !$is_super_admin ? $request->role_id : null,
            'department_id' => !$is_super_admin ? $request->department_id : null,
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('user-management.index')
            ->with('success', 'User berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        // Cegah hapus user sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('user-management.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('user-management.index')
            ->with('success', 'User berhasil dihapus!');
    }
}
