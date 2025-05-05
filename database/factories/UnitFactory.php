<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unit>
 */
class UnitFactory extends Factory
{
    protected $model = \App\Models\Unit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'property_id' => Property::factory(), // Creates a property if none exists
            'unit_number' => $this->faker->bothify('Unit ##?'),
            'unit_type' => $this->faker->randomElement(['apartment', 'studio', 'townhouse', 'condo']),
            'bedrooms' => $this->faker->numberBetween(1, 4),
            'bathrooms' => $this->faker->numberBetween(1, 3),
            'square_footage' => $this->faker->randomFloat(2, 500, 2000),
            'rent_amount' => $this->faker->randomFloat(2, 800, 5000),
            'features' => $this->faker->randomElements([
                'balcony',
                'parking',
                'in-unit laundry',
                'pet-friendly',
                'gym access',
                'pool',
            ], rand(2, 4)),
            'status' => $this->faker->randomElement(['available', 'occupied', 'maintenance']),
        ];
    }
}
