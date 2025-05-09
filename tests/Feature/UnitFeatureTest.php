<?php

use App\Models\Property;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->property = Property::factory()->create(['user_id' => $this->user->id]);
});

it('can create a unit', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($user)
        ->postJson('/api/unit', [
            'property_id' => $this->property->id,
            'unit_number' => '101',
            'unit_type' => 'Apartment',
            'bedrooms' => 2,
            'bathrooms' => 1,
            'square_footage' => 850.50,
            'rent_amount' => 1200.00,
            'features' => ['Balcony', 'Air Conditioning'],
            'status' => 'vacant',
        ]);

    $response->assertCreated()
        ->assertJson([
            'unit_number' => '101',
            'status' => 'vacant',
            'property_id' => $this->property->id
        ]);

    $this->assertDatabaseHas('units', [
        'unit_number' => '101',
        'property_id' => $this->property->id
    ]);
});

it('belongs to property', function () {
    $unit = Unit::factory()
        ->for($this->property)
        ->create([
            'unit_number' => '101',
            'status' => 'vacant'
        ]);

    $user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($user)
        ->getJson("/api/unit/{$unit->id}")
        ->assertOk()
        ->assertJson([
            'property_id' => $this->property->id,
            'unit_number' => '101'
        ]);

    expect($unit->property)
        ->toBeInstanceOf(Property::class)
        ->and($unit->property->id)->toBe($this->property->id);
});

it('validates unit status enum', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($user)
        ->postJson('/api/unit', [
            'property_id' => $this->property->id,
            'unit_number' => '102',
            'status' => 'invalid-status',
            'unit_type' => 'Apartment',
            'bedrooms' => 1,
            'rent_amount' => 1000.00
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['status']);
});
