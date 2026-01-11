<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::withCount('permissions')->orderBy('name')->get();
        return view('user-management.index', compact('users'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('group')->orderBy('display_name')->get()->groupBy('group');
        return view('user-management.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'is_super_admin' => ['boolean'],
            'is_active' => ['boolean'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_super_admin' => $request->boolean('is_super_admin'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Sync permissions jika bukan super admin
        if (!$user->is_super_admin && $request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        return redirect()->route('user-management.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        $permissions = Permission::orderBy('group')->orderBy('display_name')->get()->groupBy('group');
        $userPermissionIds = $user->permissions->pluck('id')->toArray();

        return view('user-management.edit', compact('user', 'permissions', 'userPermissionIds'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'is_super_admin' => ['boolean'],
            'is_active' => ['boolean'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id']
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_super_admin' => $request->boolean('is_super_admin'),
            'is_active' => $request->boolean('is_active'),
        ]);

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        // Sync permissions jika bukan super admin
        if (!$user->is_super_admin && $request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        } elseif (!$user->is_super_admin) {
            $user->syncPermissions([]);
        }

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
