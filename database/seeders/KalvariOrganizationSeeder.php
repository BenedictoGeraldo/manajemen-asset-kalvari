<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Department;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class KalvariOrganizationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Roles
        $roles = [
            ['slug' => 'pastor', 'name' => 'Pastor', 'description' => 'Pastor Paroki / Rekan'],
            ['slug' => 'dph', 'name' => 'DPH', 'description' => 'Dewan Paroki Harian'],
            ['slug' => 'sekretariat', 'name' => 'Sekretariat', 'description' => 'Tim Sekretariat'],
            ['slug' => 'bendahara', 'name' => 'Bendahara', 'description' => 'Tim Bendahara'],
            ['slug' => 'koordinator-bidang', 'name' => 'Koordinator Bidang', 'description' => 'Koordinator Bidang'],
            ['slug' => 'ketua-seksi', 'name' => 'Ketua Seksi', 'description' => 'Ketua Seksi / Sub Seksi'],
            ['slug' => 'tim-kerja', 'name' => 'Tim Kerja', 'description' => 'Anggota Tim Kerja / Staff'],
        ];

        foreach ($roles as $r) {
            Role::updateOrCreate(['slug' => $r['slug']], $r);
        }

        // 2. Clear existing organizational data to avoid duplicates
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::where('is_super_admin', false)->forceDelete();
        Department::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 3. Define Departments Hierarchy
        $root = Department::create(['code' => 'ROOT', 'name' => 'Paroki Kalvari Lubang Buaya', 'type' => 'Root']);

        // Top Level Groups
        $depts = [
            '0A' => ['name' => 'Pastorat', 'parent' => $root->id],
            '0B' => ['name' => 'Wakil Ketua Dewan', 'parent' => $root->id],
            '0C' => ['name' => 'Sekretariat', 'parent' => $root->id],
            '0D' => ['name' => 'Bendahara', 'parent' => $root->id],
            '10' => ['name' => 'Bidang Peribadatan', 'parent' => $root->id],
            '20' => ['name' => 'Bidang Pewartaan', 'parent' => $root->id],
            '30' => ['name' => 'Bidang Pelayanan', 'parent' => $root->id],
            '40' => ['name' => 'Bidang Persekutuan', 'parent' => $root->id],
            '50' => ['name' => 'Bidang Kesaksian', 'parent' => $root->id],
            '60' => ['name' => 'Bidang Penelitian dan Pengembangan', 'parent' => $root->id],
            '70' => ['name' => 'Bidang Perencanaan dan Evaluasi', 'parent' => $root->id],
            '80' => ['name' => 'Bidang Pengembangan Gereja', 'parent' => $root->id],
            '90' => ['name' => 'Bidang Digital', 'parent' => $root->id],
            'A0' => ['name' => 'Bidang Kategorial dan Teritorial', 'parent' => $root->id],
        ];

        $deptModels = [];
        foreach ($depts as $code => $data) {
            $deptModels[$code] = Department::create([
                'code' => $code,
                'name' => $data['name'],
                'parent_id' => $data['parent'],
                'type' => 'Bidang'
            ]);
        }

        // Sub-Departments (Seksi)
        $subDepts = [
            '1A' => ['name' => 'Sie Liturgi', 'parent' => '10'],
            '1B' => ['name' => 'Sie Paramenta', 'parent' => '10'],
            '1C' => ['name' => 'Sie Hias Altar', 'parent' => '10'],
            '2A' => ['name' => 'Sie Katekese', 'parent' => '20'],
            '2B' => ['name' => 'Sie Kerasulan Kitab Suci', 'parent' => '20'],
            '3A' => ['name' => 'Sie Pengembangan Sosial Ekonomi', 'parent' => '30'],
            '3B' => ['name' => 'Sie Santo Yusuf', 'parent' => '30'],
            '3C' => ['name' => 'Sie Kesehatan', 'parent' => '30'],
            '3D' => ['name' => 'Sie Pendidikan', 'parent' => '30'],
            '4A' => ['name' => 'Sie Kerasulan Keluarga', 'parent' => '40'],
            '4B' => ['name' => 'Sie Panggilan', 'parent' => '40'],
            '4C' => ['name' => 'Sie Kepemudaan', 'parent' => '40'],
            '5A' => ['name' => 'Sie Keadilan Perdamaian', 'parent' => '50'],
            '5B' => ['name' => 'Sie SKP 2 - LH', 'parent' => '50'],
            '5C' => ['name' => 'Sie HAAK urusan Bekasi', 'parent' => '50'],
            '5D' => ['name' => 'Sie HAAK urusan Timur', 'parent' => '50'],
            '5E' => ['name' => 'Sie Hubungan Kemasyarakatan', 'parent' => '50'],
            '6A' => ['name' => 'Sie Pelatihan dan Kaderisasi', 'parent' => '60'],
            '6B' => ['name' => 'Sie Penelitian dan Pengembangan', 'parent' => '60'],
            '7A' => ['name' => 'Sie Perencanaan', 'parent' => '70'],
            '7B' => ['name' => 'Sie Evaluasi', 'parent' => '70'],
            '8A' => ['name' => 'Sie Keamanan', 'parent' => '80'],
            '8B' => ['name' => 'Seksi Pemeliharaan Gedung & Taman', 'parent' => '80'],
            '8C' => ['name' => 'Seksi Pemeliharaan Multimedia', 'parent' => '80'],
            '9A' => ['name' => 'Sie Kalvari Media', 'parent' => '90'],
            '9B' => ['name' => 'Sie Multimedia', 'parent' => '90'],
            '9C' => ['name' => 'Sie Dokumentasi', 'parent' => '90'],
            '9D' => ['name' => 'Sie IT Kreatif', 'parent' => '90'],
        ];

        foreach ($subDepts as $code => $data) {
            $deptModels[$code] = Department::create([
                'code' => $code,
                'name' => $data['name'],
                'parent_id' => $deptModels[$data['parent']]->id,
                'type' => 'Seksi'
            ]);
        }

        // 4. Personnel Data (Users)
        $usersData = [
            ['code' => '0A-01', 'name' => 'RD Johan Ferdinand Wijshijer', 'role' => 'pastor', 'parent_code' => '0A'],
            ['code' => '0A-02', 'name' => 'RD Gregorius Wilson', 'role' => 'pastor', 'parent_code' => '0A'],
            ['code' => '0B-01', 'name' => 'Petrus Baron Purwanto', 'role' => 'dph', 'parent_code' => '0B'],
            ['code' => '0B-02', 'name' => 'Fransiscus Xaverius Susilo Hudiono', 'role' => 'dph', 'parent_code' => '0B'],
            ['code' => '0B-03', 'name' => 'Gabriella Felisitas', 'role' => 'dph', 'parent_code' => '0B'],
            ['code' => '0B-11', 'name' => 'FX Purwoto', 'role' => 'tim-kerja', 'parent_code' => '0B'],
            ['code' => '0B-12', 'name' => 'Mi Villiana', 'role' => 'tim-kerja', 'parent_code' => '0B'],
            ['code' => '0C-01', 'name' => 'Fiki Sabbas Purnomo', 'role' => 'sekretariat', 'parent_code' => '0C'],
            ['code' => '0C-02', 'name' => 'Ferdinandus Agung', 'role' => 'sekretariat', 'parent_code' => '0C'],
            ['code' => '0C-00', 'name' => 'Robertus Djati Kartiko Sarjono', 'role' => 'ketua-seksi', 'parent_code' => '0C'],
            ['code' => '0C-11', 'name' => 'Anastasia Promosiana', 'role' => 'tim-kerja', 'parent_code' => '0C'],
            ['code' => '0C-12', 'name' => 'Gregorius Ernest Buntoro', 'role' => 'tim-kerja', 'parent_code' => '0C'],
            ['code' => '0D-01', 'name' => 'Fransisca Maria Santy Ekawati', 'role' => 'bendahara', 'parent_code' => '0D'],
            ['code' => '0D-02', 'name' => 'Gregorius Yorrie Riswanto', 'role' => 'bendahara', 'parent_code' => '0D'],
            ['code' => '0D-11', 'name' => 'Yulius Purwanto', 'role' => 'tim-kerja', 'parent_code' => '0D'],
            ['code' => '10-01', 'name' => 'Benedictus Martono', 'role' => 'koordinator-bidang', 'parent_code' => '10'],
            ['code' => '20-01', 'name' => 'Adrianus Nara Lamadua', 'role' => 'koordinator-bidang', 'parent_code' => '20'],
            ['code' => '30-01', 'name' => 'Yuliana Lestari Mugi Handayani', 'role' => 'koordinator-bidang', 'parent_code' => '30'],
            ['code' => '40-01', 'name' => 'Yacobus Wirawan', 'role' => 'koordinator-bidang', 'parent_code' => '40'],
            ['code' => '50-01', 'name' => 'Johanes Paulus Budi Waskito', 'role' => 'koordinator-bidang', 'parent_code' => '50'],
            ['code' => '70-01', 'name' => 'Bernadette Maria Uma Parwati', 'role' => 'koordinator-bidang', 'parent_code' => '70'],
            ['code' => '80-01', 'name' => 'Yohanes Lie', 'role' => 'koordinator-bidang', 'parent_code' => '80'],
            ['code' => '90-01', 'name' => 'Antonius Karno Tjhin', 'role' => 'koordinator-bidang', 'parent_code' => '90'],
            ['code' => '1A-00', 'name' => 'Jemmy Ransi Rante Galla', 'role' => 'ketua-seksi', 'parent_code' => '1A'],
            ['code' => '1B-00', 'name' => 'Andreas Agung Wijayanto', 'role' => 'ketua-seksi', 'parent_code' => '1B'],
            ['code' => '1C-00', 'name' => 'Maria Imakulata Herny Alfrida Sulistia', 'role' => 'ketua-seksi', 'parent_code' => '1C'],
            ['code' => '1A-01', 'name' => 'Benedictus Suryawan', 'role' => 'ketua-seksi', 'parent_code' => '1A'],
            ['code' => '1A-02', 'name' => 'Florentine Widiastuti', 'role' => 'ketua-seksi', 'parent_code' => '1A'],
            ['code' => '1A-03', 'name' => 'Fransiska Suminar', 'role' => 'ketua-seksi', 'parent_code' => '1A'],
            ['code' => '1A-04', 'name' => 'Lucius Busono Wikanto', 'role' => 'ketua-seksi', 'parent_code' => '1A'],
            ['code' => '1A-05', 'name' => 'Angela Fitria', 'role' => 'ketua-seksi', 'parent_code' => '1A'],
            ['code' => '1A-06', 'name' => 'Rayner Raphael Rudianto', 'role' => 'ketua-seksi', 'parent_code' => '1A'],
            ['code' => '2A-00', 'name' => 'Siprianus Ayahama', 'role' => 'ketua-seksi', 'parent_code' => '2A'],
            ['code' => '2A-01', 'name' => 'Hilarius Hariyanto', 'role' => 'ketua-seksi', 'parent_code' => '2A'],
            ['code' => '2A-02', 'name' => 'Maria Prudentiana Moi', 'role' => 'ketua-seksi', 'parent_code' => '2A'],
            ['code' => '2B-00', 'name' => 'Sarma Romauli Siregar', 'role' => 'ketua-seksi', 'parent_code' => '2B'],
            ['code' => '9A-00', 'name' => 'Yohanes Bintang', 'role' => 'ketua-seksi', 'parent_code' => '9A'],
            ['code' => '9B-00', 'name' => 'Andreas Aditya', 'role' => 'ketua-seksi', 'parent_code' => '9B'],
            ['code' => '9C-00', 'name' => 'Nico Fuadi', 'role' => 'ketua-seksi', 'parent_code' => '9C'],
            ['code' => '9D-00', 'name' => 'Alexander Hadwinning Arso', 'role' => 'ketua-seksi', 'parent_code' => '9D'],
            ['code' => '3A-00', 'name' => 'A. Dyah Purwanti', 'role' => 'ketua-seksi', 'parent_code' => '3A'],
            ['code' => '3A-11', 'name' => 'Aurea Utami Dyah Condrosari', 'role' => 'tim-kerja', 'parent_code' => '3A'],
            ['code' => '3B-00', 'name' => 'Johanes Purwanto R', 'role' => 'ketua-seksi', 'parent_code' => '3B'],
            ['code' => '3C-00', 'name' => 'dr. Gregorius Agung Budi Setiawan', 'role' => 'ketua-seksi', 'parent_code' => '3C'],
            ['code' => '3D-00', 'name' => 'Ellya Elkawati', 'role' => 'ketua-seksi', 'parent_code' => '3D'],
            ['code' => '3D-11', 'name' => 'Theresia Ria Kusumaningrum', 'role' => 'tim-kerja', 'parent_code' => '3D'],
            ['code' => '4A-00', 'name' => 'Heribertus Suparyanto', 'role' => 'ketua-seksi', 'parent_code' => '4A'],
            ['code' => '4B-00', 'name' => 'Veronica Suratemi', 'role' => 'ketua-seksi', 'parent_code' => '4B'],
            ['code' => '4C-00', 'name' => 'Veronica Roro Sekar Wening', 'role' => 'ketua-seksi', 'parent_code' => '4C'],
            ['code' => '4C-11', 'name' => 'Gabrielle Deandhra Anetta Putri', 'role' => 'tim-kerja', 'parent_code' => '4C'],
            ['code' => '5A-00', 'name' => 'Thomas Ragha, S.H', 'role' => 'ketua-seksi', 'parent_code' => '5A'],
            ['code' => '5B-00', 'name' => 'Antonia Riwindri Astuti', 'role' => 'ketua-seksi', 'parent_code' => '5B'],
            ['code' => '5C-00', 'name' => 'Richardus P Ray Radja', 'role' => 'ketua-seksi', 'parent_code' => '5C'],
            ['code' => '5D-00', 'name' => 'Mayang', 'role' => 'ketua-seksi', 'parent_code' => '5D'],
            ['code' => '5E-00', 'name' => 'Y Sunaryo', 'role' => 'ketua-seksi', 'parent_code' => '5E'],
            ['code' => '6A-00', 'name' => 'L. Indra Dewanto', 'role' => 'ketua-seksi', 'parent_code' => '6A'],
            ['code' => '6B-00', 'name' => 'Wisnu Pranastya', 'role' => 'ketua-seksi', 'parent_code' => '6B'],
            ['code' => '7A-00', 'name' => 'JF Putri Novita I', 'role' => 'ketua-seksi', 'parent_code' => '7A'],
            ['code' => '7B-00', 'name' => 'A. Emi Nugraheni', 'role' => 'ketua-seksi', 'parent_code' => '7B'],
            ['code' => '8A-00', 'name' => 'Rosana Dharwati Sinuraya', 'role' => 'ketua-seksi', 'parent_code' => '8A'],
            ['code' => '8B-00', 'name' => 'Albertus Hengky Yulianto', 'role' => 'ketua-seksi', 'parent_code' => '8B'],
            ['code' => '8C-00', 'name' => 'Ignatius Dimas Prasetyo', 'role' => 'ketua-seksi', 'parent_code' => '8C'],
            ['code' => '8C-11', 'name' => 'Fransisca Warsitiatun', 'role' => 'tim-kerja', 'parent_code' => '8C'],
        ];

        // Deduplicate users to prevent unique constraint errors
        $uniqueUsers = collect($usersData)->unique(function ($item) {
            return Str::slug($item['name'], '.');
        });

        $roleModels = Role::all()->keyBy('slug');
        $defaultPassword = Hash::make('password');

        foreach ($uniqueUsers as $u) {
            $username = Str::slug($u['name'], '.');
            if (empty($username)) continue;
            
            User::updateOrCreate(
                ['username' => $username],
                [
                    'name' => $u['name'],
                    'email' => $username . '@kalvari.org',
                    'password' => $defaultPassword,
                    'role_id' => $roleModels[$u['role']]->id,
                    'department_id' => $deptModels[$u['parent_code']]->id,
                    'is_active' => true,
                ]
            );
        }
    }
}
