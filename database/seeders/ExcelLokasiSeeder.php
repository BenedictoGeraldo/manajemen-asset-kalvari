<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\MasterLokasi;

class ExcelLokasiSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key checks untuk sementara (jika ada relasi dengan data_aset kolektif yang masih kosong)
        Schema::disableForeignKeyConstraints();
        
        // Hapus data lama
        MasterLokasi::truncate();
        
        Schema::enableForeignKeyConstraints();

        $data = [
            ['Gereja', 'Area Umat Gereja', '0A'],
            ['Gereja', 'Altar Utama', '0B'],
            ['Gereja', 'Ruang Pengakuan dosa 1', '0C'],
            ['Gereja', 'Ruang Pengakuan dosa 2', '0D'],
            ['Gereja', 'Ruang Pengakuan dosa 3', '0E'],
            ['Gereja', 'Ruang Pengakuan dosa 4', '0F'],
            ['Gereja', 'Ruang Busui', '0G'],
            ['Gereja', 'Ruang komsos', '0H'],
            ['Gereja', 'Loby gereja', '0J'],
            ['Gereja', 'Toilet difabel selatan', '0K'],
            ['Gereja', 'Toilet difabel utara', '0L'],
            ['Gereja', 'Toilet pria selatan', '0M'],
            ['Gereja', 'Toilet pria utara', '0N'],
            ['Gereja', 'Toilet wanita selatan', '0O'],
            ['Gereja', 'Toilet wanita utara', '0P'],
            ['Gereja', 'Lorong selatan', '0Q'],
            ['Gereja', 'Lorong utara', '0R'],
            ['Gereja', 'Janitor selatan', '0S'],
            ['Gereja', 'Janitor utara', '0T'],
            
            ['Kapel', 'Area Umat Kapel', '1A'],
            ['Kapel', 'Altar Kapel', '1B'],
            ['Kapel', 'Ruang Ganti Romo', '1C'],
            ['Kapel', 'Ruang Ganti Prodiakon', '1D'],
            ['Kapel', 'Ruang Ganti Misdinar&lektor&pemazmu', '1E'],
            ['Kapel', 'Lorong kapel', '1F'],
            
            ['Sekretariat', 'Ruang tunggu sekretariat & Lorong', '2A'],
            ['Sekretariat', 'Ruang Sekretariat', '2B'],
            ['Sekretariat', 'Ruang Romo 1', '2C'],
            ['Sekretariat', 'Ruang Romo 2', '2D'],
            ['Sekretariat', 'Ruang meeting 1', '2E'],
            ['Sekretariat', 'Gudang', '2F'],
            ['Sekretariat', 'Office 1 (mas agus)', '2G'],
            ['Sekretariat', 'Ruang Keuangan', '2H'],
            ['Sekretariat', 'Pantry', '2J'],
            ['Sekretariat', 'Toilet', '2K'],
            ['Sekretariat', 'Lorong tangga utara', '2L'],
            ['Sekretariat', 'Lorong tangga selatan', '2M'],
            ['Sekretariat', 'Loby Sekretariat', '2N'],
            ['Sekretariat', 'Ruang kecil loby', '2O'],
            ['Sekretariat', 'Ruang tangga utara', '2P'],
            ['Sekretariat', 'Ruang tangga selatan', '2Q'],
            ['Sekretariat', 'Panel Utara', '2R'],
            ['Sekretariat', 'Panel Selatan', '2S'],
            
            ['Pastoran', 'Ruang tengah', '3A'],
            ['Pastoran', 'Kamar romo Fe', '3B'],
            ['Pastoran', 'Kamar romo wilson', '3C'],
            ['Pastoran', 'Kamar tamu 1', '3D'],
            ['Pastoran', 'Kamar tamu 2', '3E'],
            
            ['Taman & Teras', 'Taman Doa', '5A'],
            ['Taman & Teras', 'Sacrarium', '5B'],
            ['Taman & Teras', 'Makam yesus', '5C'],
            ['Taman & Teras', 'Monumen Kalvari', '5D'],
            ['Taman & Teras', 'Lorong Kebun Anggur', '5E'],
            ['Taman & Teras', 'Jalan Salib', '5F'],
            
            ['Ruangan', 'Aula Mulut Emas', '4A'],
            ['Ruangan', 'Aula Vincentius', '4B'],
            ['Ruangan', 'Aula Benedictus', '4C'],
            ['Ruangan', 'Kios eujin', '4D'],
            ['Ruangan', 'Kios Inigo 1', '4E'],
            ['Ruangan', 'Kios Inigo 2', '4F'],
            ['Ruangan', 'Lorong vincentius - mulut emas', '4G'],
            ['Ruangan', 'Lorong benedictus - mulut emas', '4H'],
            ['Ruangan', 'Ruang belakang aula emas', '4J'],
            ['Ruangan', 'Gudang kecil belakang aula emas 1', '4K'],
            ['Ruangan', 'Gudang kecil belakang aula emas 2', '4L'],
            ['Ruangan', 'Ruang hana', '4M'],
            ['Ruangan', 'Ruang simeon', '4N'],
            ['Ruangan', 'Lorong simeon hana', '4O'],
            ['Ruangan', 'Ruang akses luar Vincentius', '4P'],
            ['Ruangan', 'Ruang kecil di Vincentius', '4Q'],
            
            ['Basement', 'Parkiran Kendaraan', '6A'],
            ['Basement', 'Ruang arnoldus genset', '6B'],
            ['Basement', 'Ruang chevalier air', '6C'],
            ['Basement', 'Ruang Komsos carlo acotis', '6D'],
            ['Basement', 'Ruang liturgl', '6E'],
            ['Basement', 'Kapel Maria ASSUMPTA', '6F'],
            ['Basement', 'Gudang Kecil belakang Goa Maria 1', '6G'],
            ['Basement', 'Gudang Kecil belakang Goa Maria 2', '6H'],
            ['Basement', 'Ruang kecil 1 Sound Mulut Emas', '6I'],
            ['Basement', 'Ruang kecil 2 vincentius mulut emas', '6J'],
            ['Basement', 'Ruang kecil 3 vincentius mulut emas', '6K'],
            ['Basement', 'Ruang pinggir 1', '6L'],
            ['Basement', 'Ruang ganti misdinar', '6M'],
            ['Basement', 'Kamar mandi utara', '6N'],
            ['Basement', 'Gudang ruang pinggir 2 (lorong simeon h...', '6O'],
            ['Basement', 'Ruang panel listrik benedictus mulut ema', '6P'],
            ['Basement', 'Ruang kecil parkiran 1 (Utara)', '6Q'],
            ['Basement', 'Ruang kecil parkiran 2 (Utara)', '6R'],
            ['Basement', 'Ruang kecil parkiran 3 (Selatan)', '6S'],
            ['Basement', 'Ruang kecil parkiran 4 (Selatan)', '6T'],
            ['Basement', 'Toilet pria selatan', '6U'],
            ['Basement', 'Toilet pria utara', '6V'],
            ['Basement', 'Toilet wanita selatan', '6W'],
            ['Basement', 'Toilet wanita utara', '6X'],
            ['Basement', 'Janitor Utara', '6Y'],
            ['Basement', 'Kamar Mandi Selatan', '6Z'],
            
            ['Klinik', 'Loby Klinik', '7A'],
            ['Klinik', 'Klinik 1', '7B'],
            ['Klinik', 'Klinik 2', '7C'],
            ['Klinik', 'Klinik 3', '7D'],
            ['Klinik', 'Klinik 4', '7E'],
            ['Klinik', 'Lorong klinik', '7F'],
            ['Klinik', 'Toilet', '7G'],
            
            ['Area Luar', 'Pos Security pintu yahya', '8A'],
            ['Area Luar', 'Roof Top selatan', '8B'],
            ['Area Luar', 'Roof Top utara', '8C'],
            ['Area Luar', 'jalan sisi utara', '8D'],
            ['Area Luar', 'jalan sisi selatan', '8E'],
            ['Area Luar', 'jalan loby gereja', '8F'],
            ['Area Luar', 'jalan taman doa', '8G'],
            ['Area Luar', 'parkiran gereja lama', '8H'],
            ['Area Luar', 'parkiran konblok', '8J'],
            ['Area Luar', 'jalan parkiran Gereja Lama', '8K'],
            ['Area Luar', 'pintu markus', '8L'],
            ['Area Luar', 'Parkiran Motor Depan', '8M'],
            ['Area Luar', 'Pos Security pintu lukas', '8N'],
            
            ['Pastoran Lama', 'Pastoran lama', '9A']
        ];

        $now = now();
        $insertData = [];

        foreach ($data as $item) {
            $insertData[] = [
                'nama_lokasi' => $item[0],
                'sub_lokasi' => $item[1],
                'kode_lokasi' => $item[2],
                'keterangan_lokasi' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        MasterLokasi::insert($insertData);
    }
}
