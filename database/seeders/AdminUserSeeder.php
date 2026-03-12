<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Cafe Admin',
            'email' => 'admin@cafe.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'role' => 'admin',
            'phone' => '1234567890',
        ]);
        
        echo "Admin user created successfully!\n";
        echo "Email: admin@cafe.com\n";
        echo "Password: admin123\n";
    }
}