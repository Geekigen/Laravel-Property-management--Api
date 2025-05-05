<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        // Create 10 properties (each owned by a landlord) and assign 2–5 units to each
        User::factory()->landlord()->count(10)->create()->each(function ($landlord) {
            Property::factory()->count(1)->create([
                'user_id' => $landlord->id,
            ])->each(function ($property) {d
                Unit::factory()->count(rand(2, 5))->create([
                    'property_id' => $property->id,
                    'status' => $this->randomStatus(['vacant', 'occupied', 'maintenance', 'reserved']),
                ]);
            });
        });

        // Create a specific property with 3 specific units
        $specificLandlord = User::factory()->landlord()->create([
            'name' => 'Elite Landlord',
            'email' => 'elite.landlord@example.com',
        ]);
        $specificProperty = Property::factory()->create([
            'user_id' => $specificLandlord->id,
            'name' => 'Elite Towers',
            'active' => true,
        ]);
        Unit::factory()->create([
            'property_id' => $specificProperty->id,
            'unit_number' => 'Unit 101',
            'unit_type' => 'apartment',
            'bedrooms' => 2,
            'bathrooms' => 2,
            'square_footage' => 1200.00,
            'rent_amount' => 2000.00,
            'features' => ['balcony', 'in-unit laundry', 'pet-friendly'],
            'status' => 'vacant',
        ]);
        Unit::factory()->create([
            'property_id' => $specificProperty->id,
            'unit_number' => 'Unit 102',
            'unit_type' => 'studio',
            'bedrooms' => 1,
            'bathrooms' => 1,
            'square_footage' => 600.00,
            'rent_amount' => 1200.00,
            'features' => ['parking', 'gym access'],
            'status' => 'occupied',
        ]);
        Unit::factory()->create([
            'property_id' => $specificProperty->id,
            'unit_number' => 'Unit 103',
            'unit_type' => 'apartment',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'square_footage' => 1500.00,
            'rent_amount' => 2500.00,
            'features' => ['balcony', 'pool', 'in-unit laundry'],
            'status' => 'maintenance',
        ]);
    }

    private function randomStatus(array $statuses): string
    {
        return $statuses[array_rand($statuses)];
    }
}
