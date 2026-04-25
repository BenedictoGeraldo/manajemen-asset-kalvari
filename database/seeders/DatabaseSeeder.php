<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleAndDepartmentSeeder::class,
            KalvariOrganizationSeeder::class,
            OrganizationSettingsSeeder::class,
            KalvariMasterSeeder::class,
            ChurchLocationSeeder::class,
            ChurchManagerSeeder::class,
            UserSeeder::class,
        ]);
    }
}