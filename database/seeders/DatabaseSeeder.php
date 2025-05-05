<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Provider\ar_EG\Payment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            UserSeeder::class,
            PropertySeeder::class,
            UnitSeeder::class,
            TenantsSeeder::class,
            LeaseSeeder::class,
            PaymentsSeeder::class,

        ]);
    }
}
