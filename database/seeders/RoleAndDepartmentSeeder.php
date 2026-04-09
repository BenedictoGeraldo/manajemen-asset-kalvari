<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Department;
use App\Models\Permission;

class RoleAndDepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Departments
        $departments = [
            ['name' => 'Pemuda', 'type' => 'Komisi', 'code' => 'K-PMD'],
            ['name' => 'Remaja', 'type' => 'Komisi', 'code' => 'K-RMJ'],
            ['name' => 'Sekolah Minggu', 'type' => 'Komisi', 'code' => 'K-SM'],
            ['name' => 'Musik & Multimedia', 'type' => 'Bidang', 'code' => 'B-MSK'],
            ['name' => 'Ibadah', 'type' => 'Bidang', 'code' => 'B-IBD'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['code' => $dept['code']], $dept);
        }

        // 2. Create Roles
        $adminDivisiRole = Role::firstOrCreate(
            ['slug' => 'admin-divisi'],
            ['name' => 'Admin Divisi', 'description' => 'Admin khusus untuk mengelola aset dan transaksi divisinya sendiri']
        );

        $peminjamRole = Role::firstOrCreate(
            ['slug' => 'peminjam'],
            ['name' => 'Peminjam', 'description' => 'User biasa yang hanya bisa meminjam aset']
        );

        // 3. Assign Permissions to Admin Divisi
        // Diberikan: akses dashboard, data aset (view, create, edit, delete, export), transaksi (view, create, approve khusus divisi)
        $adminDivisiPermissions = Permission::whereIn('name', [
            'dashboard.view',
            'data-aset.view',
            'data-aset.create',
            'data-aset.edit',
            'data-aset.delete',
            'data-aset.export',
            'transaksi.peminjaman.view',
            'transaksi.peminjaman.create',
            'transaksi.peminjaman.approve',
            'transaksi.pemeliharaan.view',
            'transaksi.pemeliharaan.create',
            'transaksi.mutasi_aset.view',
            'master.kategori.view',      // Butuh view untuk master saat input
            'master.lokasi.view',
            'master.kondisi.view',
            'master.pengelola.view'
        ])->get();

        $adminDivisiRole->permissions()->sync($adminDivisiPermissions->pluck('id')->toArray());

        // 4. Assign Permissions to Peminjam
        $peminjamPermissions = Permission::whereIn('name', [
            'dashboard.view',
            'data-aset.view',
            'transaksi.peminjaman.view',
            'transaksi.peminjaman.create'
        ])->get();

        $peminjamRole->permissions()->sync($peminjamPermissions->pluck('id')->toArray());
    }
}
