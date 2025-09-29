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
        
        User::truncate();

        // Buat user baru
        User::create([
            'name' => 'IT Kreatif',
            'email' => 'itkreatif@gmail.com',
            'password' => Hash::make('itkalvari'),
        ]);
    }
}