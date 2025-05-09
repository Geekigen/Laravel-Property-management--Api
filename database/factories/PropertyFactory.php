<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word . ' ' . $this->faker->word . ' Property', // Fixed words generation
            'description' => $this->faker->sentence(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->stateAbbr(),
            'postal_code' => $this->faker->postcode(),
            'country' => 'US', // Fixed to consistent country code
            'property_type' => $this->faker->randomElement(['house', 'apartment', 'condo', 'townhouse']),
            'year_built' => $this->faker->numberBetween(1970, date('Y')),
            'active' => $this->faker->boolean(80),
        ];
    }
}
