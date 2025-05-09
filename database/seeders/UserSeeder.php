<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admiein@example.com',
        ]);

        User::factory()->agent()->count(5)->create();
        User::factory()->landlord()->count(10)->create();
        User::factory()->count(20)->create();
    }
}
