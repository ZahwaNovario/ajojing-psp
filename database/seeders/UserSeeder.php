<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ])->assignRole('admin');

        // Pegawai
        User::factory()->create([
            'name' => 'Pegawai User',
            'email' => 'pegawai@example.com',
        ])->assignRole('pegawai');

        // Customer
        User::factory()->create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
        ])->assignRole('customer');
    }
}
