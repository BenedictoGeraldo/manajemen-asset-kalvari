<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterPengelola;
use Illuminate\Support\Facades\DB;

class ChurchManagerSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Truncate existing data as requested
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        MasterPengelola::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Load departments map for lookup (Code => ID)
        $deptMap = \App\Models\Department::pluck('id', 'code')->toArray();

        $managers = [
            // DEWAN PAROKI (0A-0D)
            ['kode_pengelola' => '0A-01', 'nama_pengelola' => 'RD Johan Ferdinand Wijshijer', 'jabatan' => 'Pastor Kepala'],
            ['kode_pengelola' => '0A-02', 'nama_pengelola' => 'RD Gregorius Wilson', 'jabatan' => 'Pastor Rekan'],
            ['kode_pengelola' => '0B-01', 'nama_pengelola' => 'Petrus Baron Purwanto', 'jabatan' => 'Wakil Ketua Dewan I'],
            ['kode_pengelola' => '0B-02', 'nama_pengelola' => 'Fransiscus Xaverius Susilo Hudiono', 'jabatan' => 'Wakil Ketua Dewan II'],
            ['kode_pengelola' => '0B-03', 'nama_pengelola' => 'Gabriella Felisitas', 'jabatan' => 'Wakil Ketua Dewan III'],
            ['kode_pengelola' => '0C-01', 'nama_pengelola' => 'Fiki Sabbas Purnomo', 'jabatan' => 'Sekretaris Dewan I'],
            ['kode_pengelola' => '0C-02', 'nama_pengelola' => 'Ferdinandus Agung', 'jabatan' => 'Sekretaris Dewan II'],
            ['kode_pengelola' => '0C-03', 'nama_pengelola' => '-', 'jabatan' => 'Sekretaris Dewan III'],
            ['kode_pengelola' => '0D-01', 'nama_pengelola' => 'Fransisca Maria Santy Ekawati', 'jabatan' => 'Bendahara I'],
            ['kode_pengelola' => '0D-02', 'nama_pengelola' => 'Gregorius Yorrie Riswanto', 'jabatan' => 'Bendahara II'],
            ['kode_pengelola' => '0D-03', 'nama_pengelola' => '-', 'jabatan' => 'Bendahara III'],

            // BIDANG (10-90)
            ['kode_pengelola' => '10-01', 'nama_pengelola' => 'Benedictus Martono', 'jabatan' => 'Bidang Peribadatan'],
            ['kode_pengelola' => '20-01', 'nama_pengelola' => 'Adrianus Nara Lamadua', 'jabatan' => 'Bidang Pewartaan'],
            ['kode_pengelola' => '30-01', 'nama_pengelola' => 'Yuliana Lestari Mugi Handayani', 'jabatan' => 'Bidang Pelayanan'],
            ['kode_pengelola' => '40-01', 'nama_pengelola' => 'Yacobus Wirawan', 'jabatan' => 'Bidang Persekutuan'],
            ['kode_pengelola' => '50-01', 'nama_pengelola' => 'Johanes Paulus Budi Waskito', 'jabatan' => 'Bidang Kesaksian'],
            ['kode_pengelola' => '60-01', 'nama_pengelola' => '-', 'jabatan' => 'Bidang Penelitian dan Pengembangan'],
            ['kode_pengelola' => '70-01', 'nama_pengelola' => 'Bernadette Maria Uma Parwati', 'jabatan' => 'Bidang Perencanaan dan Evaluasi'],
            ['kode_pengelola' => 'A0-01', 'nama_pengelola' => '-', 'jabatan' => 'Bidang Kategorial dan Teritorial'],
            ['kode_pengelola' => '80-01', 'nama_pengelola' => 'Yohanes Lie', 'jabatan' => 'Bidang Pengembangan Gereja'],
            ['kode_pengelola' => '90-01', 'nama_pengelola' => 'Antonius Karno Tjhin', 'jabatan' => 'Bidang Digital'],

            // SEKSI & TIM KERJA
            ['kode_pengelola' => '1A-00', 'nama_pengelola' => 'Jemmy Ransi Rante Galla', 'jabatan' => 'Sie Liturgi'],
            ['kode_pengelola' => '1B-00', 'nama_pengelola' => 'Andreas Agung Wijayanto', 'jabatan' => 'Sie Paramenta'],
            ['kode_pengelola' => '1C-00', 'nama_pengelola' => 'Maria Imakulata Herny Alfrida Sulistia', 'jabatan' => 'Sie Hias Altar'],
            ['kode_pengelola' => '1A-01', 'nama_pengelola' => 'Benedictus Suryawan', 'jabatan' => 'Sub Sie Prodiakon'],
            ['kode_pengelola' => '1A-02', 'nama_pengelola' => 'Florentine Widiastuti', 'jabatan' => 'Sub Sie Pasdior'],
            ['kode_pengelola' => '1A-03', 'nama_pengelola' => 'Fransiska Suminar', 'jabatan' => 'Sub Sie Lektor'],
            ['kode_pengelola' => '1A-04', 'nama_pengelola' => 'Lucius Busono Wikanto', 'jabatan' => 'Sub Sie Tata Laksana'],
            ['kode_pengelola' => '1A-05', 'nama_pengelola' => 'Angela Fitria', 'jabatan' => 'Sub Sie Pemazmur'],
            ['kode_pengelola' => '1A-06', 'nama_pengelola' => 'Rayner Raphael Rudianto', 'jabatan' => 'Sub Sie Putra Putri altar'],
            ['kode_pengelola' => '2A-00', 'nama_pengelola' => 'Siprianus Ayahama', 'jabatan' => 'Sie Katekese'],
            ['kode_pengelola' => '2A-01', 'nama_pengelola' => 'Hilarius Hariyanto', 'jabatan' => 'Sub Sie Sakramen Inisiasi'],
            ['kode_pengelola' => '2A-02', 'nama_pengelola' => 'Maria Prudentiana Moi', 'jabatan' => 'Sub Sie BIA/BIR'],
            ['kode_pengelola' => '2B-00', 'nama_pengelola' => 'Sarma Romauli Siregar', 'jabatan' => 'Sie Kerasulan Kitab Suci'],
            ['kode_pengelola' => '9A-00', 'nama_pengelola' => 'Yohanes Bintang', 'jabatan' => 'Sie Kalvari Media'],
            ['kode_pengelola' => '9B-00', 'nama_pengelola' => 'Andreas Aditya', 'jabatan' => 'Sie Multimedia'],
            ['kode_pengelola' => '9C-00', 'nama_pengelola' => 'Nico Fuadi', 'jabatan' => 'Sie Dokumentasi'],
            ['kode_pengelola' => '9D-00', 'nama_pengelola' => 'Alexander Hadwinning Arso', 'jabatan' => 'Sie IT Kreatif'],
            ['kode_pengelola' => '3A-00', 'nama_pengelola' => 'A. Dyah Purwanti', 'jabatan' => 'Sie Pengembangan Sosial Ekonomi'],
            ['kode_pengelola' => '3B-00', 'nama_pengelola' => 'Johanes Purwanto R', 'jabatan' => 'Sie Santo Yusuf'],
            ['kode_pengelola' => '3C-00', 'nama_pengelola' => 'dr. Gregorius Agung Budi Setiawan', 'jabatan' => 'Sie Kesehatan'],
            ['kode_pengelola' => '3D-00', 'nama_pengelola' => 'Ellya Elkawati', 'jabatan' => 'Sie Pendidikan'],
            ['kode_pengelola' => '4A-00', 'nama_pengelola' => 'Heribertus Suparyanto dan Yohana Soliga Sartika DS', 'jabatan' => 'Sie Kerasulan Keluarga'],
            ['kode_pengelola' => '4B-00', 'nama_pengelola' => 'Veronica Suratemi', 'jabatan' => 'Sie Panggilan'],
            ['kode_pengelola' => '4C-00', 'nama_pengelola' => 'Veronica Roro Sekar Wening', 'jabatan' => 'Sie Kepemudaan'],
            ['kode_pengelola' => '5A-00', 'nama_pengelola' => 'Thomas Ragha, S.H', 'jabatan' => 'Sie Keadilan Perdamaian'],
            ['kode_pengelola' => '5B-00', 'nama_pengelola' => 'Antonia Riwindri Astuti', 'jabatan' => 'Sie SKP 2-LH'],
            ['kode_pengelola' => '5C-00', 'nama_pengelola' => 'Richardus P Ray Radja', 'jabatan' => 'Sie HAAK urusan Bekasi'],
            ['kode_pengelola' => '5D-00', 'nama_pengelola' => 'Mayang', 'jabatan' => 'Sie HAAK Urusan Timur'],
            ['kode_pengelola' => '5E-00', 'nama_pengelola' => 'Y Sunaryo', 'jabatan' => 'Sie Hubungan Kemasyarakatan'],
            ['kode_pengelola' => '6A-00', 'nama_pengelola' => 'L. Indra Dewanto', 'jabatan' => 'Sie Pelatihan dan Kaderisasi'],
            ['kode_pengelola' => '6B-00', 'nama_pengelola' => 'Wisnu Pranastya', 'jabatan' => 'Sie Penelitian dan Pengembangan'],
            ['kode_pengelola' => '7A-00', 'nama_pengelola' => 'JF Putri Novita I', 'jabatan' => 'Sie Perencanaan'],
            ['kode_pengelola' => '7B-00', 'nama_pengelola' => 'A. Emi Nugraheni', 'jabatan' => 'Sie Evaluasi'],
            ['kode_pengelola' => '0C-00', 'nama_pengelola' => 'Robertus Djati Kartiko Sarjono', 'jabatan' => 'Sie Kekaryawanan'],
            ['kode_pengelola' => '8A-00', 'nama_pengelola' => 'Rosana Dharwati Sinuraya', 'jabatan' => 'Sie Keamanan'],
            ['kode_pengelola' => '8B-00', 'nama_pengelola' => 'Albertus Hengky Yulianto', 'jabatan' => 'Seksi Pemeliharaan dan perawatan Gedung dan taman'],
            ['kode_pengelola' => '8C-00', 'nama_pengelola' => 'Ignatius Dimas Prasetyo', 'jabatan' => 'Seksi Pemeliharaan dan perawatan Multimedia'],
            ['kode_pengelola' => '8C-11', 'nama_pengelola' => 'Fransisca Warsitiatun', 'jabatan' => 'Tim Rumah Tangga Pastor'],
            ['kode_pengelola' => '0C-11', 'nama_pengelola' => 'Anastasia Promosiana', 'jabatan' => 'Tim Arsip'],
            ['kode_pengelola' => '0C-12', 'nama_pengelola' => 'Gregorius Ernest Buntoro', 'jabatan' => 'Tim Data Biduk'],
            ['kode_pengelola' => '0B-11', 'nama_pengelola' => 'FX Purwoto', 'jabatan' => 'Tim Audit Internal'],
            ['kode_pengelola' => '0B-12', 'nama_pengelola' => 'MI Villiana', 'jabatan' => 'Tim Audit Internal'],
            ['kode_pengelola' => '0D-11', 'nama_pengelola' => 'Yulius Purwanto', 'jabatan' => 'Tim Asset Paroki'],
            ['kode_pengelola' => '3D-11', 'nama_pengelola' => 'Theresia Ria Kusumaningrum', 'jabatan' => 'Tim ASAK'],
            ['kode_pengelola' => '4C-11', 'nama_pengelola' => 'Gabrielle Deandhra Anetta Putri', 'jabatan' => 'Ketua OMK'],
            ['kode_pengelola' => '3A-11', 'nama_pengelola' => 'Aurea Utami Dyah Condrosari', 'jabatan' => 'Tim UMKM'],
        ];

        foreach ($managers as $manager) {
            $prefix = explode('-', $manager['kode_pengelola'])[0] ?? '';
            $manager['department_id'] = $deptMap[$prefix] ?? ($deptMap[trim($prefix)] ?? null);
            
            MasterPengelola::create($manager);
        }
    }
}
