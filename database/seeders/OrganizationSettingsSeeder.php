<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class OrganizationSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            [
                'key' => 'org_name',
                'value' => 'Kalvari Lubang Buaya',
                'group' => 'general',
                'type' => 'text',
                'label' => 'Nama Pendek Organisasi'
            ],
            [
                'key' => 'org_full_name',
                'value' => 'Paroki Kalvari Lubang Buaya',
                'group' => 'general',
                'type' => 'text',
                'label' => 'Nama Lengkap Organisasi'
            ],
            [
                'key' => 'org_address',
                'value' => 'Jl. Lubang Buaya No.1, Jakarta Timur',
                'group' => 'general',
                'type' => 'textarea',
                'label' => 'Alamat'
            ],
            [
                'key' => 'org_email',
                'value' => 'sekretariat@kalvari.org',
                'group' => 'general',
                'type' => 'text',
                'label' => 'Email'
            ],
            [
                'key' => 'org_phone',
                'value' => '(021) 12345678',
                'group' => 'general',
                'type' => 'text',
                'label' => 'Nomor Telepon'
            ],
            [
                'key' => 'org_logo',
                'value' => null,
                'group' => 'general',
                'type' => 'image',
                'label' => 'Logo Organisasi'
            ],
            // Branding
            [
                'key' => 'app_name',
                'value' => 'Manajemen Aset Kalvari',
                'group' => 'branding',
                'type' => 'text',
                'label' => 'Nama Aplikasi'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
