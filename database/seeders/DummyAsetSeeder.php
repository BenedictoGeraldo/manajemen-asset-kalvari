<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\DataAsetService;
use App\Models\MasterKategori;
use App\Models\MasterKondisi;
use App\Models\MasterLokasi;
use App\Models\MasterPengelola;
use App\Models\Department;

class DummyAsetSeeder extends Seeder
{
    public function run(): void
    {
        $service = new DataAsetService();
        $faker = \Faker\Factory::create('id_ID');

        // Fetch available master data to maintain relational integrity
        $kategoris = MasterKategori::pluck('id')->toArray();
        $kondisis = MasterKondisi::pluck('id')->toArray();
        $lokasis = MasterLokasi::pluck('id')->toArray();
        $pengelolas = MasterPengelola::whereNotNull('department_id')->get();

        if (empty($kategoris) || empty($kondisis) || empty($lokasis) || $pengelolas->isEmpty()) {
            $this->command->warn('Master data (Kategori, Kondisi, Lokasi, Pengelola) must be seeded first.');
            return;
        }

        $assetNames = [
            'Laptop Operasional', 'Meja Rapat', 'Kursi Jemaat', 'AC Split', 
            'Proyektor Gereja', 'Speaker Monitor', 'Printer Lasar', 'Lemari Dokumen',
            'Sofa Ruang Tunggu', 'Microphone Wireless'
        ];

        // Generate 20 random assets
        for ($i = 0; $i < 20; $i++) {
            $pengelola = $pengelolas->random();
            
            $data = [
                'nama_aset' => $faker->randomElement($assetNames) . ' ' . $faker->numberBetween(1, 100),
                'kategori_id' => $faker->randomElement($kategoris),
                'department_id' => $pengelola->department_id,
                'lokasi_id' => $faker->randomElement($lokasis),
                'pengelola_id' => $pengelola->id,
                'kondisi_id' => $faker->randomElement($kondisis),
                'deskripsi_aset' => 'Data Aset Dummy untuk testing Batch Print QR Code',
                'ukuran' => 'Standard',
                'kegunaan' => 'Fasilitas dan Operasional',
                'tipe_grup' => 'individual',
                'jumlah_barang' => 1,
                'nilai_budget' => $faker->numberBetween(500000, 10000000),
                'nilai_pengadaan_total' => $faker->numberBetween(500000, 10000000),
                'nilai_pengadaan_per_unit' => $faker->numberBetween(500000, 10000000),
                'tahun_pengadaan' => date('Y'),
                'sumber_dana' => 'Kas Gereja',
                'is_active' => true,
            ];
            
            $service->createAset($data);
        }

        $this->command->info('20 Dummy Assets have been generated successfully for QR Code testing.');
    }
}
