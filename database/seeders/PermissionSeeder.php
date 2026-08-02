<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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

            // Department Management
            [
                'name' => 'departments.view',
                'display_name' => 'Lihat Departemen',
                'group' => 'Pengaturan',
                'description' => 'Dapat melihat daftar departemen'
            ],
            [
                'name' => 'departments.create',
                'display_name' => 'Tambah Departemen',
                'group' => 'Pengaturan',
                'description' => 'Dapat menambah departemen baru'
            ],
            [
                'name' => 'departments.edit',
                'display_name' => 'Edit Departemen',
                'group' => 'Pengaturan',
                'description' => 'Dapat mengedit data departemen'
            ],
            [
                'name' => 'departments.delete',
                'display_name' => 'Hapus Departemen',
                'group' => 'Pengaturan',
                'description' => 'Dapat menghapus departemen'
            ],

            // Role Management
            [
                'name' => 'roles.view',
                'display_name' => 'Lihat Role',
                'group' => 'Pengaturan',
                'description' => 'Dapat melihat daftar role'
            ],
            [
                'name' => 'roles.create',
                'display_name' => 'Tambah Role',
                'group' => 'Pengaturan',
                'description' => 'Dapat menambah role baru'
            ],
            [
                'name' => 'roles.edit',
                'display_name' => 'Edit Role',
                'group' => 'Pengaturan',
                'description' => 'Dapat mengedit role dan hak aksesnya'
            ],
            [
                'name' => 'roles.delete',
                'display_name' => 'Hapus Role',
                'group' => 'Pengaturan',
                'description' => 'Dapat menghapus role'
            ],

            // Permission Management
            [
                'name' => 'permissions.view',
                'display_name' => 'Lihat Daftar Hak Akses',
                'group' => 'Pengaturan',
                'description' => 'Dapat melihat semua daftar hak akses yang ada'
            ],

            // Laporan
            [
                'name' => 'laporan.data-aset.view',
                'slug' => 'laporan.data-aset.view',
                'display_name' => 'Lihat Laporan Data Aset',
                'group' => 'Laporan',
                'description' => 'Dapat melihat laporan data aset'
            ],
            [
                'name' => 'laporan.mutasi-aset.view',
                'slug' => 'laporan.mutasi-aset.view',
                'display_name' => 'Lihat Laporan Mutasi Aset',
                'group' => 'Laporan',
                'description' => 'Dapat melihat laporan mutasi aset'
            ],
            [
                'name' => 'laporan.pembelian.view',
                'slug' => 'laporan.pembelian.view',
                'display_name' => 'Lihat Laporan Pembelian',
                'group' => 'Laporan',
                'description' => 'Dapat melihat laporan pembelian'
            ],
            [
                'name' => 'laporan.pemusnahan.view',
                'slug' => 'laporan.pemusnahan.view',
                'display_name' => 'Lihat Laporan Pemusnahan',
                'group' => 'Laporan',
                'description' => 'Dapat melihat laporan pemusnahan'
            ],
            [
                'name' => 'laporan.print-qr.view',
                'slug' => 'laporan.print-qr.view',
                'display_name' => 'Cetak QR Code',
                'group' => 'Laporan',
                'description' => 'Dapat mencetak QR Code aset'
            ],

            // Transaksi Pembelian
            [
                'name' => 'transaksi.pembelian.view',
                'slug' => 'transaksi.pembelian.view',
                'display_name' => 'Lihat Pembelian',
                'group' => 'Data Transaksional',
                'description' => 'Dapat melihat daftar transaksi pembelian'
            ],
            [
                'name' => 'transaksi.pembelian.create',
                'slug' => 'transaksi.pembelian.create',
                'display_name' => 'Tambah Pembelian',
                'group' => 'Data Transaksional',
                'description' => 'Dapat menambah transaksi pembelian baru'
            ],
            [
                'name' => 'transaksi.pembelian.edit',
                'slug' => 'transaksi.pembelian.edit',
                'display_name' => 'Edit Pembelian',
                'group' => 'Data Transaksional',
                'description' => 'Dapat mengedit transaksi pembelian'
            ],
            [
                'name' => 'transaksi.pembelian.delete',
                'slug' => 'transaksi.pembelian.delete',
                'display_name' => 'Hapus Pembelian',
                'group' => 'Data Transaksional',
                'description' => 'Dapat menghapus transaksi pembelian'
            ],
            [
                'name' => 'transaksi.pembelian.approve',
                'slug' => 'transaksi.pembelian.approve',
                'display_name' => 'Setujui Pembelian',
                'group' => 'Data Transaksional',
                'description' => 'Dapat menyetujui pembelian dan posting ke data aset'
            ],
            [
                'name' => 'transaksi.pembelian.reject',
                'slug' => 'transaksi.pembelian.reject',
                'display_name' => 'Tolak Pembelian',
                'group' => 'Data Transaksional',
                'description' => 'Dapat menolak transaksi pembelian'
            ],
            [
                'name' => 'transaksi.pembelian.export',
                'slug' => 'transaksi.pembelian.export',
                'display_name' => 'Export Pembelian',
                'group' => 'Data Transaksional',
                'description' => 'Dapat mengexport data transaksi pembelian ke Excel/CSV'
            ],

            // Transaksi Peminjaman
            [
                'name' => 'transaksi.peminjaman.view',
                'slug' => 'transaksi.peminjaman.view',
                'display_name' => 'Lihat Peminjaman',
                'group' => 'Data Transaksional',
                'description' => 'Dapat melihat daftar transaksi peminjaman'
            ],
            [
                'name' => 'transaksi.peminjaman.export',
                'slug' => 'transaksi.peminjaman.export',
                'display_name' => 'Export Peminjaman',
                'group' => 'Data Transaksional',
                'description' => 'Dapat mengexport data transaksi peminjaman ke Excel/CSV'
            ],
            [
                'name' => 'transaksi.peminjaman.create',
                'slug' => 'transaksi.peminjaman.create',
                'display_name' => 'Tambah Peminjaman',
                'group' => 'Data Transaksional',
                'description' => 'Dapat menambah transaksi peminjaman baru'
            ],
            [
                'name' => 'transaksi.peminjaman.edit',
                'slug' => 'transaksi.peminjaman.edit',
                'display_name' => 'Edit Peminjaman',
                'group' => 'Data Transaksional',
                'description' => 'Dapat mengedit transaksi peminjaman'
            ],
            [
                'name' => 'transaksi.peminjaman.delete',
                'slug' => 'transaksi.peminjaman.delete',
                'display_name' => 'Hapus Peminjaman',
                'group' => 'Data Transaksional',
                'description' => 'Dapat menghapus transaksi peminjaman'
            ],
            [
                'name' => 'transaksi.peminjaman.approve',
                'slug' => 'transaksi.peminjaman.approve',
                'display_name' => 'Setujui/Tolak Peminjaman (Admin)',
                'group' => 'Data Transaksional',
                'description' => 'ADMIN: dapat menyetujui/menolak, serah terima, pengembalian, edit, hapus, dan melihat semua data'
            ],
            [
                'name' => 'transaksi.peminjaman.handover',
                'slug' => 'transaksi.peminjaman.handover',
                'display_name' => 'Serah Terima Peminjaman',
                'group' => 'Data Transaksional',
                'description' => 'Dapat memproses serah terima aset yang dipinjam (perlu akses admin)'
            ],
            [
                'name' => 'transaksi.peminjaman.return',
                'slug' => 'transaksi.peminjaman.return',
                'display_name' => 'Pengembalian Peminjaman',
                'group' => 'Data Transaksional',
                'description' => 'Dapat memproses pengembalian aset yang dipinjam (perlu akses admin)'
            ],

            // Transaksi Pemeliharaan
            [
                'name' => 'transaksi.pemeliharaan.view',
                'slug' => 'transaksi.pemeliharaan.view',
                'display_name' => 'Lihat Pemeliharaan',
                'group' => 'Data Transaksional',
                'description' => 'Dapat melihat daftar transaksi pemeliharaan'
            ],
            [
                'name' => 'transaksi.pemeliharaan.export',
                'slug' => 'transaksi.pemeliharaan.export',
                'display_name' => 'Export Pemeliharaan',
                'group' => 'Data Transaksional',
                'description' => 'Dapat mengexport data transaksi pemeliharaan ke Excel/CSV'
            ],
            [
                'name' => 'transaksi.pemeliharaan.create',
                'slug' => 'transaksi.pemeliharaan.create',
                'display_name' => 'Tambah Pemeliharaan',
                'group' => 'Data Transaksional',
                'description' => 'Dapat menambah transaksi pemeliharaan baru'
            ],
            [
                'name' => 'transaksi.pemeliharaan.edit',
                'slug' => 'transaksi.pemeliharaan.edit',
                'display_name' => 'Edit Pemeliharaan',
                'group' => 'Data Transaksional',
                'description' => 'Dapat mengedit transaksi pemeliharaan'
            ],
            [
                'name' => 'transaksi.pemeliharaan.delete',
                'slug' => 'transaksi.pemeliharaan.delete',
                'display_name' => 'Hapus Pemeliharaan',
                'group' => 'Data Transaksional',
                'description' => 'Dapat menghapus transaksi pemeliharaan'
            ],
            [
                'name' => 'transaksi.pemeliharaan.approve',
                'slug' => 'transaksi.pemeliharaan.approve',
                'display_name' => 'Setujui/Tolak Pemeliharaan (Admin)',
                'group' => 'Data Transaksional',
                'description' => 'ADMIN: dapat menyetujui/menolak, proses, selesaikan, edit, hapus, dan melihat semua data'
            ],
            [
                'name' => 'transaksi.pemeliharaan.process',
                'slug' => 'transaksi.pemeliharaan.process',
                'display_name' => 'Proses Pemeliharaan',
                'group' => 'Data Transaksional',
                'description' => 'Dapat memulai proses pemeliharaan yang telah disetujui (perlu akses admin)'
            ],
            [
                'name' => 'transaksi.pemeliharaan.complete',
                'slug' => 'transaksi.pemeliharaan.complete',
                'display_name' => 'Selesaikan Pemeliharaan',
                'group' => 'Data Transaksional',
                'description' => 'Dapat menyelesaikan pemeliharaan dan memperbarui kondisi aset (perlu akses admin)'
            ],

            // Transaksi Mutasi Aset
            [
                'name' => 'transaksi.mutasi_aset.view',
                'slug' => 'transaksi.mutasi_aset.view',
                'display_name' => 'Lihat Mutasi Aset',
                'group' => 'Data Transaksional',
                'description' => 'Dapat melihat daftar transaksi mutasi aset'
            ],
            [
                'name' => 'transaksi.mutasi_aset.export',
                'slug' => 'transaksi.mutasi_aset.export',
                'display_name' => 'Export Mutasi Aset',
                'group' => 'Data Transaksional',
                'description' => 'Dapat mengexport data transaksi mutasi aset ke Excel/CSV'
            ],
            [
                'name' => 'transaksi.mutasi_aset.create',
                'slug' => 'transaksi.mutasi_aset.create',
                'display_name' => 'Tambah Mutasi Aset',
                'group' => 'Data Transaksional',
                'description' => 'Dapat menambah transaksi mutasi aset baru'
            ],
            [
                'name' => 'transaksi.mutasi_aset.edit',
                'slug' => 'transaksi.mutasi_aset.edit',
                'display_name' => 'Edit Mutasi Aset',
                'group' => 'Data Transaksional',
                'description' => 'Dapat mengedit transaksi mutasi aset'
            ],
            [
                'name' => 'transaksi.mutasi_aset.delete',
                'slug' => 'transaksi.mutasi_aset.delete',
                'display_name' => 'Hapus Mutasi Aset',
                'group' => 'Data Transaksional',
                'description' => 'Dapat menghapus transaksi mutasi aset'
            ],
            [
                'name' => 'transaksi.mutasi_aset.approve',
                'slug' => 'transaksi.mutasi_aset.approve',
                'display_name' => 'Setujui/Tolak Mutasi Aset (Admin)',
                'group' => 'Data Transaksional',
                'description' => 'ADMIN: dapat menyetujui/menolak, proses, selesaikan, edit, hapus, dan melihat semua data'
            ],
            [
                'name' => 'transaksi.mutasi_aset.process',
                'slug' => 'transaksi.mutasi_aset.process',
                'display_name' => 'Proses Mutasi Aset',
                'group' => 'Data Transaksional',
                'description' => 'Dapat memulai proses mutasi aset yang telah disetujui (perlu akses admin)'
            ],
            [
                'name' => 'transaksi.mutasi_aset.complete',
                'slug' => 'transaksi.mutasi_aset.complete',
                'display_name' => 'Selesaikan Mutasi Aset',
                'group' => 'Data Transaksional',
                'description' => 'Dapat menyelesaikan mutasi aset dan memperbarui data aset (perlu akses admin)'
            ],

            // Transaksi Pemusnahan Aset
            [
                'name' => 'transaksi.pemusnahan.view',
                'display_name' => 'Lihat Pemusnahan Aset',
                'group' => 'Data Transaksional',
                'description' => 'Dapat melihat daftar transaksi pemusnahan aset'
            ],
            [
                'name' => 'transaksi.pemusnahan.create',
                'display_name' => 'Tambah Pemusnahan Aset',
                'group' => 'Data Transaksional',
                'description' => 'Dapat menambah transaksi pemusnahan aset baru'
            ],
            [
                'name' => 'transaksi.pemusnahan.delete',
                'display_name' => 'Hapus Pemusnahan Aset',
                'group' => 'Data Transaksional',
                'description' => 'Dapat menghapus transaksi pemusnahan aset'
            ],
            [
                'name' => 'transaksi.pemusnahan.approve',
                'display_name' => 'Setujui Pemusnahan (Admin)',
                'group' => 'Data Transaksional',
                'description' => 'ADMIN: dapat menghapus, edit, dan melihat semua data pemusnahan'
            ],
        ];

        $count = 0;
        foreach ($permissions as $permission) {
            $name = $permission['name'];
            $slug = $permission['slug'] ?? Str::slug($name);
            
            Permission::withTrashed()->updateOrCreate(
                ['name' => $name],
                array_merge($permission, ['slug' => $slug, 'deleted_at' => null])
            );
            $count++;
        }
        $jumlah = Permission::count();
        
        $this->command->info("Seeded terbaru $jumlah permissions.");
    }
}
