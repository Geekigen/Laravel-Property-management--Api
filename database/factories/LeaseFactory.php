<?php

namespace Database\Factories;

use App\Models\Lease;
use App\Models\Unit;
use App\Models\Tenants;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaseFactory extends Factory
{
    protected $model = \App\Models\Lease::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $endDate = $this->faker->dateTimeBetween($startDate, '+1 year');

        return [
            'unit_id' => null, // Seeder provides this
            'tenant_id' => null, // Seeder provides this
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'rent_amount' => $this->faker->randomFloat(2, 800, 5000),
            'security_deposit' => $this->faker->randomFloat(2, 500, 10000),
            'payment_day' => $this->faker->numberBetween(1, 28),
            'lease_type' => $this->faker->randomElement(['month-to-month', 'fixed-term']),
            'status' => $this->faker->randomElement(['active', 'expired', 'terminated', 'upcoming']),
            'terms' => $this->faker->paragraph(),
        ];
    }
}
