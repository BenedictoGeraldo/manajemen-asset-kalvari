<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        User::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // Buat user baru
        User::create([
            'name' => 'IT Kreatif',
            'email' => 'itkreatif@gmail.com',
            'password' => Hash::make('itkalvari'),
            'department_id' => 1,
            'is_super_admin' => true,
            'is_active' => true,
        ]);
    }
}