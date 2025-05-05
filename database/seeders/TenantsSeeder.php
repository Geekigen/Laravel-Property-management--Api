<?php

namespace Database\Seeders;

use App\Models\Tenants;
use Illuminate\Database\Seeder;

class TenantsSeeder extends Seeder
{
    public function run(): void
    {
        // Create 20 random tenants
        Tenants::factory()->count(20)->create();

        // Create a specific tenant
        Tenants::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'date_of_birth' => '1990-05-15',
            'id_number' => 'ID-98765432',
            'status' => 'active',
        ]);

        // Create 5 pending tenants
        Tenants::factory()->count(5)->create([
            'status' => 'blacklisted',
        ]);

        // Create 5 inactive tenants
        Tenants::factory()->count(5)->create([
            'status' => 'inactive',
        ]);
    }
}
