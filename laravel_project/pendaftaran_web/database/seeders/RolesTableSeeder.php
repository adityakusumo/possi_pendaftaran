<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // Import Spatie's Role model

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if they don't already exist to prevent duplicates on re-seeding
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'editor']);
        Role::firstOrCreate(['name' => 'user']);
        Role::firstOrCreate(['name' => 'operator']); // <-- Your new role
        // You can add more roles here as needed
    }
}
