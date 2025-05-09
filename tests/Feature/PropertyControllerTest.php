<?php

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Unauthenticated users cannot access properties index
test('unauthenticated properties index', function () {
    $response = $this->getJson('/api/property');
    $response->assertStatus(401);
});

// Authenticated users can access properties index
test('authenticated properties index', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($user);

    Property::factory()->count(3)->create();

    $response = $this->getJson('/api/property');
    $response->assertStatus(200);
});

// Authenticated users can create a property
test('authenticated create property', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($user);

    $data = [
        'id' => 1, // Assuming you want to set this manually for testing
        'user_id' => $user->id,
        'name' => 'Sample Property',
        'description' => 'A beautiful sample property.',
        'address' => '123 Main St',
        'city' => 'Sample City',
        'state' => 'Sample State',
        'postal_code' => '12345',
        'country' => 'Sample Country',
        'property_type' => 'Residential',
        'year_built' => 2000,
        'active' => true,
    ];

    $response = $this->postJson('/api/property', $data);
    $response->assertStatus(201);
    $this->assertDatabaseHas('properties', $data);
});

// Authenticated users can update a property
test('authenticated update property', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($user);

    $property = Property::factory()->create();

    $data = [
        'name' => 'Updated Property',
        'city' => 'Updated City',
    ];

    $response = $this->putJson("/api/property/{$property->id}", $data);
    $response->assertStatus(200);
    $this->assertDatabaseHas('properties', $data);
});

// Authenticated users can delete a property
test('authenticated delete property', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($user);

    $property = Property::factory()->create();

    $response = $this->deleteJson("/api/property/{$property->id}");
    $response->assertStatus(200);
    $this->assertDatabaseMissing('properties', ['id' => $property->id]);
});

// Authenticated users can view a specific property
test('authenticated view property', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($user);

    $property = Property::factory()->create();

    $response = $this->getJson("/api/property/{$property->id}");
    $response->assertStatus(200);
});

// Authenticated users receive 404 for non-existent property
test('authenticated view non-existent property', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($user);

    $response = $this->getJson('/api/property/999');
    $response->assertStatus(404);
    $response->assertJson([
        'error' => 'Property not found',
    ]);
});

