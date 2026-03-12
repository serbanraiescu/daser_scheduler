<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create SuperAdmin
        \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
        ]);

        // Create a sample Admin
        \App\Models\User::factory()->create([
            'name' => 'Salon Owner',
            'email' => 'owner@salon.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create sample Employee
        \App\Models\User::factory()->create([
            'name' => 'John Barber',
            'email' => 'john@salon.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
        ]);

        // Create sample Client
        \App\Models\User::factory()->create([
            'name' => 'Regular Client',
            'email' => 'client@client.com',
            'password' => bcrypt('password'),
            'role' => 'client',
        ]);
    }
}
