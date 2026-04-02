<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@senco.edu'], // Check if this email exists
            [
                'name' => 'Senco Admin',
                'password' => Hash::make('admin123'), // Encrypts the password
                'email_verified_at' => now(),
                'role' => 'Admin',
            ]
        );
    }
}