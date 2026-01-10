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
        User::create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@perpustakaan.test',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'User Perpustakaan',
            'email' => 'user@perpustakaan.test',
            'password' => Hash::make('password123'),
        ]);
    }
}
