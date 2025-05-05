<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TenantsFactory extends Factory
{
    protected $model = \App\Models\Tenants::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'date_of_birth' => $this->faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'id_number' => $this->faker->unique()->numerify('ID-########'),
            'status' => $this->faker->randomElement(['active', 'blacklisted', 'inactive']),
        ];
    }

}
