<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterLokasi;

class ChurchLocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            // GEREJA (0x)
            ['kode_lokasi' => '0A', 'nama_lokasi' => 'Area Umat Gereja', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0B', 'nama_lokasi' => 'Altar Utama', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0C', 'nama_lokasi' => 'Ruang Pengakuan dosa 1', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0D', 'nama_lokasi' => 'Ruang Pengakuan dosa 2', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0E', 'nama_lokasi' => 'Ruang Pengakuan dosa 3', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0F', 'nama_lokasi' => 'Ruang Pengakuan dosa 4', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0G', 'nama_lokasi' => 'Ruang Besuk', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0H', 'nama_lokasi' => 'Ruang komsos', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0I', 'nama_lokasi' => 'Lobby gereja', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0K', 'nama_lokasi' => 'Toilet difabel selatan', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0L', 'nama_lokasi' => 'Toilet difabel utara', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0M', 'nama_lokasi' => 'Toilet pria selatan', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0N', 'nama_lokasi' => 'Toilet pria utara', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0O', 'nama_lokasi' => 'Toilet wanita selatan', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0P', 'nama_lokasi' => 'Toilet wanita utara', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0Q', 'nama_lokasi' => 'Lorong selatan', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0R', 'nama_lokasi' => 'Lorong utara', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0S', 'nama_lokasi' => 'Janitor selatan', 'gedung' => 'Gereja'],
            ['kode_lokasi' => '0T', 'nama_lokasi' => 'Janitor utara', 'gedung' => 'Gereja'],

            // KAPEL (1x)
            ['kode_lokasi' => '1A', 'nama_lokasi' => 'Area Umat Kapel', 'gedung' => 'Kapel'],
            ['kode_lokasi' => '1B', 'nama_lokasi' => 'Altar Kapel', 'gedung' => 'Kapel'],
            ['kode_lokasi' => '1C', 'nama_lokasi' => 'Ruang Ganti Romo', 'gedung' => 'Kapel'],
            ['kode_lokasi' => '1D', 'nama_lokasi' => 'Ruang Ganti Prodiakon', 'gedung' => 'Kapel'],
            ['kode_lokasi' => '1E', 'nama_lokasi' => 'Ruang Ganti Misdinar&Sektor&Pemazam', 'gedung' => 'Kapel'],
            ['kode_lokasi' => '1F', 'nama_lokasi' => 'Lorong kapel', 'gedung' => 'Kapel'],

            // SEKRETARIAT (2x)
            ['kode_lokasi' => '2A', 'nama_lokasi' => 'Ruang tunggu sekretariat & Lorong', 'gedung' => 'Sekretariat'],
            ['kode_lokasi' => '2B', 'nama_lokasi' => 'Ruang Sekretariat', 'gedung' => 'Sekretariat'],
            ['kode_lokasi' => '2C', 'nama_lokasi' => 'Ruang Romo 1', 'gedung' => 'Sekretariat'],
            ['kode_lokasi' => '2D', 'nama_lokasi' => 'Ruang Romo 2', 'gedung' => 'Sekretariat'],
            ['kode_lokasi' => '2E', 'nama_lokasi' => 'Ruang meeting 1', 'gedung' => 'Sekretariat'],
            ['kode_lokasi' => '2F', 'nama_lokasi' => 'Gudang', 'gedung' => 'Sekretariat'],
            ['kode_lokasi' => '2G', 'nama_lokasi' => 'Office 1 (mas agus)', 'gedung' => 'Sekretariat'],
            ['kode_lokasi' => '2H', 'nama_lokasi' => 'Ruang Keuangan', 'gedung' => 'Sekretariat'],
            ['kode_lokasi' => '2J', 'nama_lokasi' => 'Pantry', 'gedung' => 'Sekretariat'],
            ['kode_lokasi' => '2K', 'nama_lokasi' => 'Toilet', 'gedung' => 'Sekretariat'],
            ['kode_lokasi' => '2L', 'nama_lokasi' => 'Lorong tangga utara', 'gedung' => 'Sekretariat'],
            ['kode_lokasi' => '2M', 'nama_lokasi' => 'Lorong tangga selatan', 'gedung' => 'Sekretariat'],
            ['kode_lokasi' => '2N', 'nama_lokasi' => 'Lobby Sekretariat', 'gedung' => 'Sekretariat'],
            ['kode_lokasi' => '2O', 'nama_lokasi' => 'Ruang kecil lobby', 'gedung' => 'Sekretariat'],
            ['kode_lokasi' => '2P', 'nama_lokasi' => 'Ruang tangga utara', 'gedung' => 'Sekretariat'],
            ['kode_lokasi' => '2Q', 'nama_lokasi' => 'Ruang tangga selatan', 'gedung' => 'Sekretariat'],
            ['kode_lokasi' => '2R', 'nama_lokasi' => 'Panel Utara', 'gedung' => 'Sekretariat'],
            ['kode_lokasi' => '2S', 'nama_lokasi' => 'Panel Selatan', 'gedung' => 'Sekretariat'],

            // PASTORAN (3x)
            ['kode_lokasi' => '3A', 'nama_lokasi' => 'Ruang tengah', 'gedung' => 'Pastoran'],
            ['kode_lokasi' => '3B', 'nama_lokasi' => 'Kamar romo fx', 'gedung' => 'Pastoran'],
            ['kode_lokasi' => '3C', 'nama_lokasi' => 'Kamar romo wilson', 'gedung' => 'Pastoran'],
            ['kode_lokasi' => '3D', 'nama_lokasi' => 'Kamar tamu 1', 'gedung' => 'Pastoran'],
            ['kode_lokasi' => '3E', 'nama_lokasi' => 'Kamar tamu 2', 'gedung' => 'Pastoran'],

            // RUANGAN BASEMENT (4x)
            ['kode_lokasi' => '4A', 'nama_lokasi' => 'Aula Mulut Emas', 'gedung' => 'Ruangan Basement'],
            ['kode_lokasi' => '4B', 'nama_lokasi' => 'Aula Vincentius', 'gedung' => 'Ruangan Basement'],
            ['kode_lokasi' => '4C', 'nama_lokasi' => 'Aula Benedictus', 'gedung' => 'Ruangan Basement'],
            ['kode_lokasi' => '4D', 'nama_lokasi' => 'Kios cuci', 'gedung' => 'Ruangan Basement'],
            ['kode_lokasi' => '4E', 'nama_lokasi' => 'Kios inigo 1', 'gedung' => 'Ruangan Basement'],
            ['kode_lokasi' => '4F', 'nama_lokasi' => 'Kios inigo 2', 'gedung' => 'Ruangan Basement'],
            ['kode_lokasi' => '4G', 'nama_lokasi' => 'Lorong vincentius - mulut emas', 'gedung' => 'Ruangan Basement'],
            ['kode_lokasi' => '4H', 'nama_lokasi' => 'Lorong benedictius - mulut emas', 'gedung' => 'Ruangan Basement'],
            ['kode_lokasi' => '4I', 'nama_lokasi' => 'Ruang belakang aula emas', 'gedung' => 'Ruangan Basement'],
            ['kode_lokasi' => '4J', 'nama_lokasi' => 'Gudang kecil belakang aula emas 1', 'gedung' => 'Ruangan Basement'],
            ['kode_lokasi' => '4K', 'nama_lokasi' => 'Gudang kecil belakang aula emas 2', 'gedung' => 'Ruangan Basement'],
            ['kode_lokasi' => '4M', 'nama_lokasi' => 'Ruang hansa', 'gedung' => 'Ruangan Basement'],
            ['kode_lokasi' => '4N', 'nama_lokasi' => 'Ruang simeon', 'gedung' => 'Ruangan Basement'],
            ['kode_lokasi' => '4O', 'nama_lokasi' => 'Lorong simeon hansa', 'gedung' => 'Ruangan Basement'],
            ['kode_lokasi' => '4P', 'nama_lokasi' => 'Ruang pisparkum Vincentius', 'gedung' => 'Ruangan Basement'],
            ['kode_lokasi' => '4Q', 'nama_lokasi' => 'Ruang kecil di Vincentius', 'gedung' => 'Ruangan Basement'],

            // TAMAN DOA (5x)
            ['kode_lokasi' => '5A', 'nama_lokasi' => 'Taman-Taman Doa', 'gedung' => 'Taman Doa'],
            ['kode_lokasi' => '5B', 'nama_lokasi' => 'Sacrarium', 'gedung' => 'Taman Doa'],
            ['kode_lokasi' => '5C', 'nama_lokasi' => 'Makam yesus', 'gedung' => 'Taman Doa'],
            ['kode_lokasi' => '5D', 'nama_lokasi' => 'Monumen Kalvari', 'gedung' => 'Taman Doa'],
            ['kode_lokasi' => '5E', 'nama_lokasi' => 'Lorong Kebun Anggur', 'gedung' => 'Taman Doa'],
            ['kode_lokasi' => '5F', 'nama_lokasi' => 'Jalan Salib', 'gedung' => 'Taman Doa'],

            // BASEMENT (6x)
            ['kode_lokasi' => '6A', 'nama_lokasi' => 'Parkiran Kendaraan', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6B', 'nama_lokasi' => 'Ruang arnolduz ganset', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6C', 'nama_lokasi' => 'Ruang chevalier air', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6D', 'nama_lokasi' => 'Ruang Komsos carlo acutis', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6E', 'nama_lokasi' => 'Ruang liturgi', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6F', 'nama_lokasi' => 'Kapel Maria ASSUMPTA', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6G', 'nama_lokasi' => 'Gudang kecil belakang Goa Maria 1', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6H', 'nama_lokasi' => 'Gudang kecil belakang Goa Maria 2', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6I', 'nama_lokasi' => 'Ruang kecil 1 Sound Mulut Emas', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6J', 'nama_lokasi' => 'Ruang kecil 2 vincentius mulut emas', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6K', 'nama_lokasi' => 'Ruang kecil 3 vincentius mulut emas', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6L', 'nama_lokasi' => 'Ruang pinggir 1', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6M', 'nama_lokasi' => 'Ruang ganti misdinar', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6N', 'nama_lokasi' => 'Kamar mandi utara', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6O', 'nama_lokasi' => 'Gudang ruang pinggir 2 (lorong simeon)', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6P', 'nama_lokasi' => 'Ruang panel listrik benedictus mulut emas', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6Q', 'nama_lokasi' => 'Ruang kecil parkiran 1 (Utara)', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6R', 'nama_lokasi' => 'Ruang kecil parkiran 2 (Utara)', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6S', 'nama_lokasi' => 'Ruang kecil parkiran 3 (Selatan)', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6T', 'nama_lokasi' => 'Ruang kecil parkiran 4 (Selatan)', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6U', 'nama_lokasi' => 'Toilet pria selatan', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6V', 'nama_lokasi' => 'Toilet pria utara', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6W', 'nama_lokasi' => 'Toilet wanita selatan', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6X', 'nama_lokasi' => 'Toilet wanita utara', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6Y', 'nama_lokasi' => 'Janitor Utara', 'gedung' => 'Basement'],
            ['kode_lokasi' => '6Z', 'nama_lokasi' => 'Kamar Mandi Selatan', 'gedung' => 'Basement'],

            // KLINIK (7x)
            ['kode_lokasi' => '7A', 'nama_lokasi' => 'Lobby Klinik', 'gedung' => 'Klinik'],
            ['kode_lokasi' => '7B', 'nama_lokasi' => 'Klinik 1', 'gedung' => 'Klinik'],
            ['kode_lokasi' => '7C', 'nama_lokasi' => 'Klinik 2', 'gedung' => 'Klinik'],
            ['kode_lokasi' => '7D', 'nama_lokasi' => 'Klinik 3', 'gedung' => 'Klinik'],
            ['kode_lokasi' => '7E', 'nama_lokasi' => 'Klinik 4', 'gedung' => 'Klinik'],
            ['kode_lokasi' => '7F', 'nama_lokasi' => 'Lorong klinik', 'gedung' => 'Klinik'],
            ['kode_lokasi' => '7G', 'nama_lokasi' => 'Toilet', 'gedung' => 'Klinik'],

            // AREA LUAR (8x)
            ['kode_lokasi' => '8A', 'nama_lokasi' => 'Pos Security pintu yahya', 'gedung' => 'Area Luar'],
            ['kode_lokasi' => '8B', 'nama_lokasi' => 'Roof Top selatan', 'gedung' => 'Area Luar'],
            ['kode_lokasi' => '8C', 'nama_lokasi' => 'Roof Top utara', 'gedung' => 'Area Luar'],
            ['kode_lokasi' => '8D', 'nama_lokasi' => 'Jalan sisi utara', 'gedung' => 'Area Luar'],
            ['kode_lokasi' => '8E', 'nama_lokasi' => 'Jalan sisi selatan', 'gedung' => 'Area Luar'],
            ['kode_lokasi' => '8F', 'nama_lokasi' => 'Jalan lobby gereja', 'gedung' => 'Area Luar'],
            ['kode_lokasi' => '8G', 'nama_lokasi' => 'Jalan taman doa', 'gedung' => 'Area Luar'],
            ['kode_lokasi' => '8H', 'nama_lokasi' => 'Parkiran gereja lama', 'gedung' => 'Area Luar'],
            ['kode_lokasi' => '8I', 'nama_lokasi' => 'Parkiran konblock', 'gedung' => 'Area Luar'],
            ['kode_lokasi' => '8J', 'nama_lokasi' => 'Jalan parkiran Gereja Lama', 'gedung' => 'Area Luar'],
            ['kode_lokasi' => '8L', 'nama_lokasi' => 'Pintu markus', 'gedung' => 'Area Luar'],
            ['kode_lokasi' => '8M', 'nama_lokasi' => 'Parkiran Motor Depan', 'gedung' => 'Area Luar'],
            ['kode_lokasi' => '8N', 'nama_lokasi' => 'Pos Security pintu lukas', 'gedung' => 'Area Luar'],

            // PASTORAN LAMA (9x)
            ['kode_lokasi' => '9A', 'nama_lokasi' => 'Pastoran Lama', 'gedung' => 'Pastoran Lama'],
        ];

        foreach ($locations as $location) {
            // Map legacy shape (nama_lokasi + gedung) into current schema.
            $payload = [
                'kode_lokasi' => $location['kode_lokasi'],
                'nama_lokasi' => $location['gedung'],
                'sub_lokasi' => $location['nama_lokasi'],
                'keterangan_lokasi' => null,
                'is_active' => true,
            ];

            MasterLokasi::updateOrCreate(
                ['kode_lokasi' => $location['kode_lokasi']],
                $payload
            );
        }
    }
}
