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
        $adminDivisiPermissions = Permission::whereIn('name', [
            'dashboard.view',
            'data-aset.view', 'data-aset.create', 'data-aset.edit', 'data-aset.delete', 'data-aset.export',
            // Data Transaksional — admin divisi dapat semua akses
            'transaksi.pembelian.view', 'transaksi.pembelian.create', 'transaksi.pembelian.edit',
            'transaksi.pembelian.delete', 'transaksi.pembelian.approve', 'transaksi.pembelian.reject',
            'transaksi.peminjaman.view', 'transaksi.peminjaman.create', 'transaksi.peminjaman.approve',
            'transaksi.pemeliharaan.view', 'transaksi.pemeliharaan.create', 'transaksi.pemeliharaan.approve',
            'transaksi.mutasi_aset.view', 'transaksi.mutasi_aset.create', 'transaksi.mutasi_aset.approve',
            // Master Data
            'master.kategori.view', 'master.lokasi.view', 'master.kondisi.view', 'master.pengelola.view',
        ])->get();

        $adminDivisiRole->permissions()->sync(
            $adminDivisiPermissions->mapWithKeys(fn($p) => [$p->id => ['can_read' => true, 'can_create' => true, 'can_update' => true, 'can_delete' => true]])->toArray()
        );

        // 4. Assign Permissions to Peminjam (user biasa: view + create sendiri saja)
        $peminjamPermissions = Permission::whereIn('name', [
            'dashboard.view',
            'data-aset.view',
            // Data Transaksional — hanya view (milik sendiri) + create
            'transaksi.pembelian.view', 'transaksi.pembelian.create',
            'transaksi.peminjaman.view', 'transaksi.peminjaman.create',
            'transaksi.pemeliharaan.view', 'transaksi.pemeliharaan.create',
            'transaksi.mutasi_aset.view', 'transaksi.mutasi_aset.create',
        ])->get();

        $peminjamRole->permissions()->sync(
            $peminjamPermissions->mapWithKeys(fn($p) => [$p->id => ['can_read' => true, 'can_create' => true, 'can_update' => false, 'can_delete' => false]])->toArray()
        );
    }
}
