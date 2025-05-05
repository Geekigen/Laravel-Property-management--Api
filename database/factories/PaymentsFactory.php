<?php

namespace Database\Factories;

use App\Models\Payments;
use App\Models\Lease;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentsFactory extends Factory
{
    protected $model = \App\Models\Payments::class;

    public function definition(): array
    {
        $dueDate = $this->faker->dateTimeBetween('-6 months', '+1 month');
        $paymentDate = $this->faker->boolean(80) ? $this->faker->dateTimeBetween($dueDate->format('Y-m-d'), $dueDate->modify('+5 days')->format('Y-m-d')) : null;

        return [
            'lease_id' => Lease::factory()->state(['status' => 'active']), // Link to an active lease
            'amount' => $this->faker->randomFloat(2, 500, 5000),
            'due_date' => $dueDate->format('Y-m-d'),
            'payment_date' => $paymentDate ? $paymentDate->format('Y-m-d') : null,
            'payment_method' => $this->faker->randomElement(['credit_card', 'bank_transfer', 'cash', 'check']),
            'transaction_id' => $this->faker->unique()->numerify('TXN-########'),
            'status' => $paymentDate ? 'paid' : $this->faker->randomElement(['pending', 'overdue']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
