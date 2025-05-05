<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->landlord(), // Create a landlord user
            'name' => $this->faker->company() . ' Property',
            'description' => $this->faker->paragraph(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'property_type' => $this->faker->randomElement(['house', 'apartment', 'condo', 'townhouse']),
            'year_built' => $this->faker->numberBetween(1970, 2025),
            'active' => $this->faker->boolean(80),
        ];
    }
}
