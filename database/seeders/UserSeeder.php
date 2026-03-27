<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists to prevent duplicate seeding errors
        if (!\App\Models\User::where('email', 'admin@shoeworkshop.com')->exists()) {
            \App\Models\User::create([
                'name' => 'Super Admin',
                'email' => 'admin@shoeworkshop.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }
    }
}
