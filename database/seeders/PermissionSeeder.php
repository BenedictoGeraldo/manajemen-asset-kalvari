<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            [
                'name' => 'dashboard.view',
                'display_name' => 'Lihat Dashboard',
                'group' => 'Dashboard',
                'description' => 'Dapat melihat halaman dashboard'
            ],

            // Data Aset
            [
                'name' => 'data-aset.view',
                'display_name' => 'Lihat Data Aset',
                'group' => 'Data Aset',
                'description' => 'Dapat melihat daftar data aset'
            ],
            [
                'name' => 'data-aset.create',
                'display_name' => 'Tambah Data Aset',
                'group' => 'Data Aset',
                'description' => 'Dapat menambah data aset baru'
            ],
            [
                'name' => 'data-aset.edit',
                'display_name' => 'Edit Data Aset',
                'group' => 'Data Aset',
                'description' => 'Dapat mengedit data aset'
            ],
            [
                'name' => 'data-aset.delete',
                'display_name' => 'Hapus Data Aset',
                'group' => 'Data Aset',
                'description' => 'Dapat menghapus data aset'
            ],
            [
                'name' => 'data-aset.export',
                'display_name' => 'Export Data Aset',
                'group' => 'Data Aset',
                'description' => 'Dapat mengexport data aset ke Excel/CSV'
            ],

            // Master Kategori
            [
                'name' => 'master.kategori.view',
                'display_name' => 'Lihat Master Kategori',
                'group' => 'Master Data',
                'description' => 'Dapat melihat master kategori'
            ],
            [
                'name' => 'master.kategori.create',
                'display_name' => 'Tambah Master Kategori',
                'group' => 'Master Data',
                'description' => 'Dapat menambah kategori baru'
            ],
            [
                'name' => 'master.kategori.edit',
                'display_name' => 'Edit Master Kategori',
                'group' => 'Master Data',
                'description' => 'Dapat mengedit kategori'
            ],
            [
                'name' => 'master.kategori.delete',
                'display_name' => 'Hapus Master Kategori',
                'group' => 'Master Data',
                'description' => 'Dapat menghapus kategori'
            ],

            // Master Lokasi
            [
                'name' => 'master.lokasi.view',
                'display_name' => 'Lihat Master Lokasi',
                'group' => 'Master Data',
                'description' => 'Dapat melihat master lokasi'
            ],
            [
                'name' => 'master.lokasi.create',
                'display_name' => 'Tambah Master Lokasi',
                'group' => 'Master Data',
                'description' => 'Dapat menambah lokasi baru'
            ],
            [
                'name' => 'master.lokasi.edit',
                'display_name' => 'Edit Master Lokasi',
                'group' => 'Master Data',
                'description' => 'Dapat mengedit lokasi'
            ],
            [
                'name' => 'master.lokasi.delete',
                'display_name' => 'Hapus Master Lokasi',
                'group' => 'Master Data',
                'description' => 'Dapat menghapus lokasi'
            ],

            // Master Kondisi
            [
                'name' => 'master.kondisi.view',
                'display_name' => 'Lihat Master Kondisi',
                'group' => 'Master Data',
                'description' => 'Dapat melihat master kondisi'
            ],
            [
                'name' => 'master.kondisi.create',
                'display_name' => 'Tambah Master Kondisi',
                'group' => 'Master Data',
                'description' => 'Dapat menambah kondisi baru'
            ],
            [
                'name' => 'master.kondisi.edit',
                'display_name' => 'Edit Master Kondisi',
                'group' => 'Master Data',
                'description' => 'Dapat mengedit kondisi'
            ],
            [
                'name' => 'master.kondisi.delete',
                'display_name' => 'Hapus Master Kondisi',
                'group' => 'Master Data',
                'description' => 'Dapat menghapus kondisi'
            ],

            // Master Pengelola
            [
                'name' => 'master.pengelola.view',
                'display_name' => 'Lihat Master Pengelola',
                'group' => 'Master Data',
                'description' => 'Dapat melihat master pengelola'
            ],
            [
                'name' => 'master.pengelola.create',
                'display_name' => 'Tambah Master Pengelola',
                'group' => 'Master Data',
                'description' => 'Dapat menambah pengelola baru'
            ],
            [
                'name' => 'master.pengelola.edit',
                'display_name' => 'Edit Master Pengelola',
                'group' => 'Master Data',
                'description' => 'Dapat mengedit pengelola'
            ],
            [
                'name' => 'master.pengelola.delete',
                'display_name' => 'Hapus Master Pengelola',
                'group' => 'Master Data',
                'description' => 'Dapat menghapus pengelola'
            ],

            // User Management
            [
                'name' => 'user-management.view',
                'display_name' => 'Lihat Manajemen User',
                'group' => 'Pengaturan',
                'description' => 'Dapat melihat daftar user'
            ],
            [
                'name' => 'user-management.create',
                'display_name' => 'Tambah User',
                'group' => 'Pengaturan',
                'description' => 'Dapat menambah user baru'
            ],
            [
                'name' => 'user-management.edit',
                'display_name' => 'Edit User',
                'group' => 'Pengaturan',
                'description' => 'Dapat mengedit user dan hak akses'
            ],
            [
                'name' => 'user-management.delete',
                'display_name' => 'Hapus User',
                'group' => 'Pengaturan',
                'description' => 'Dapat menghapus user'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
