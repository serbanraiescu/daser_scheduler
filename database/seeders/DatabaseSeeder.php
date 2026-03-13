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
        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'superadmin',
        ]);

        // Create a sample Admin
        \App\Models\User::create([
            'name' => 'Salon Owner',
            'email' => 'owner@salon.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create sample Employee
        \App\Models\User::create([
            'name' => 'John Barber',
            'email' => 'john@salon.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'employee',
        ]);

        // Create sample Client
        \App\Models\User::create([
            'name' => 'Regular Client',
            'email' => 'client@client.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'client',
        ]);
    }
}
