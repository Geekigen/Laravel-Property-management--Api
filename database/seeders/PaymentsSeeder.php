<?php

namespace Database\Seeders;

use App\Models\Payments;
use App\Models\Lease;
use App\Models\Unit;
use App\Models\Tenants;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PaymentsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $activeLeases = Lease::where('status', 'active')->take(10)->get();
        $activePaymentsData = [];
        foreach ($activeLeases as $lease) {
            $paymentCount = rand(1, 3);
            for ($i = 0; $i < $paymentCount; $i++) {
                $activePaymentsData[] = Payments::factory()->raw([
                    'lease_id' => $lease->id,
                    'amount' => $lease->rent_amount,
                    'status' => 'paid',
                    'due_date' => $faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
                    'payment_date' => $faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
                    'payment_method' => $faker->randomElement(['credit_card', 'bank_transfer', 'cash']),
                    'transaction_id' => 'TXN-' . $faker->unique()->numerify('########'),
                ]);
            }
        }
        Payments::insert($activePaymentsData);

        $specificLandlord = User::firstOrCreate(
            ['email' => 'prime.landlord@example.com'],
            [
                'name' => 'Prime Landlord',
                'email' => 'prime.landlord@example.com',
                'password' => bcrypt('password'),
                'role' => 'landlord',
            ]
        );
        $specificProperty = Property::factory()->create([
            'user_id' => $specificLandlord->id,
            'name' => 'Prime Residences',
            'active' => true,
        ]);
        $specificUnit = Unit::factory()->create([
            'property_id' => $specificProperty->id,
            'unit_number' => 'Unit 401',
            'status' => 'occupied',
            'rent_amount' => 2500.00,
        ]);
        $specificTenant = Tenants::factory()->create([
            'name' => 'Bob Wilson',
            'email' => 'bob.wilson@example.com',
            'date_of_birth' => '1980-07-15',
            'id_number' => 'ID-98765432',
            'status' => 'active',
        ]);
        $specificLease = Lease::factory()->create([
            'unit_id' => $specificUnit->id,
            'tenant_id' => $specificTenant->id,
            'start_date' => '2025-02-01',
            'end_date' => '2026-01-31',
            'rent_amount' => 2500.00,
            'security_deposit' => 5000.00,
            'payment_day' => 1,
            'lease_type' => 'fixed-term',
            'status' => 'active',
            'terms' => 'Standard lease terms: No smoking, no subletting without approval.',
        ]);
        $specificPaymentsData = [
            Payments::factory()->raw([
                'lease_id' => $specificLease->id,
                'amount' => 2500.00,
                'due_date' => '2025-02-01',
                'payment_date' => '2025-02-01',
                'payment_method' => 'credit_card',
                'transaction_id' => 'TXN-98765432',
                'status' => 'paid',
                'notes' => 'First month rent paid on time.',
            ]),
            Payments::factory()->raw([
                'lease_id' => $specificLease->id,
                'amount' => 2500.00,
                'due_date' => '2025-03-01',
                'payment_date' => null,
                'payment_method' => null,
                'transaction_id' => null,
                'status' => 'due',
                'notes' => 'Awaiting payment.',
            ]),
        ];
        Payments::insert($specificPaymentsData);

        $overdueLeases = Lease::where('status', 'active')->skip(10)->take(5)->get();
        $overduePaymentsData = [];
        foreach ($overdueLeases as $lease) {
            $overduePaymentsData[] = Payments::factory()->raw([
                'lease_id' => $lease->id,
                'amount' => $lease->rent_amount,
                'due_date' => $faker->dateTimeBetween('-3 months', '-1 month')->format('Y-m-d'),
                'payment_date' => null,
                'payment_method' => null,
                'transaction_id' => null,
                'status' => 'late',
                'notes' => 'Payment overdue, follow-up required.',
            ]);
        }
        Payments::insert($overduePaymentsData);
    }
}
