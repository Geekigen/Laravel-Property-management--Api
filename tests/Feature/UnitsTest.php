<?php

use App\Models\User;
use Database\Seeders\UnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ensure the 'units' table exists
    if (!Schema::hasTable('units')) {
        Schema::create('units', function ($table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('unit_number');
            $table->string('unit_type');
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->decimal('square_footage', 10, 2)->nullable();
            $table->decimal('rent_amount', 10, 2);
            $table->text('features')->nullable();
            $table->enum('status', ['vacant', 'occupied', 'maintenance', 'reserved'])->default('vacant');
            $table->timestamps();
        });
    }
});

test('units are created successfully', function () {
    $this->seed(UnitSeeder::class);
    $this->assertDatabaseHas('units', []); 
});

test('units are created with correct data', function () {
    $this->seed(UnitSeeder::class);
    $this->assertDatabaseHas('units', [
        'unit_number' => 'Unit 101',
        'unit_type' => 'apartment',
        'bedrooms' => 2,
        'bathrooms' => 2,
        'square_footage' => 1200.00,
        'rent_amount' => 2000.00,
        'features' => json_encode(['balcony', 'in-unit laundry', 'pet-friendly']),
        'status' => 'vacant',
    ]);
});

test('unauthenticated users cannot access units index', function () {
    $response = $this->getJson('/api/unit');
    $response->assertStatus(401); // Unauthorized
});

test('authenticated users can access units index', function () {
    $this->seed(UnitSeeder::class);
    $user = User::factory()->create(['role' => 'admin']); // Ensure the user has the correct role
    $response = $this->actingAs($user)->getJson('/api/unit');
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'property_id',
                'unit_number',
                'unit_type',
                'bedrooms',
                'bathrooms',
                'square_footage',
                'rent_amount',
                'features',
                'status',
                'created_at',
                'updated_at',
            ],
        ],
    ]);
});

test('unauthenticated users cannot view a unit', function () {
    $response = $this->getJson('/api/unit/1');
    $response->assertStatus(401); // Unauthorized
});

test('authenticated users can view a unit', function () {
    $this->seed(UnitSeeder::class);
    $user = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($user)->getJson('/api/unit/1');
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'id',
        'property_id',
        'unit_number',
        'unit_type',
        'bedrooms',
        'bathrooms',
        'square_footage',
        'rent_amount',
        'features',
        'status',
        'created_at',
        'updated_at',
    ]);
});

test('unauthenticated users cannot delete a unit', function () {
    $response = $this->deleteJson('/api/unit/1');
    $response->assertStatus(401);
});

test('authenticated users can delete a unit', function () {
    $this->seed(UnitSeeder::class);
    $user = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($user)->deleteJson('/api/unit/1');
    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Unit deleted successfully',
    ]);
    $this->assertDatabaseMissing('units', ['id' => 1]);
});

test('unauthenticated users cannot update a unit', function () {
    $response = $this->putJson('/api/unit/1', [
        'unit_number' => 'Unit 102',
        'unit_type' => 'apartment',
        'bedrooms' => 2,
        'bathrooms' => 2,
        'square_footage' => 1200.00,
        'rent_amount' => 2000.00,
        'features' => json_encode(['balcony', 'in-unit laundry', 'pet-friendly']),
        'status' => 'vacant',
    ]);
    $response->assertStatus(401);
});
test('authenticated users can update a unit', function () {
    $this->seed(UnitSeeder::class);
    $user = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($user)->putJson('/api/unit/1', [
        'unit_number' => 'Unit 102',
        'unit_type' => 'apartment',
        'bedrooms' => 2,
        'bathrooms' => 2,
        'square_footage' => 1200.00,
        'rent_amount' => 2000.00,
        'features' => ['balcony', 'in-unit laundry', 'pet-friendly'],
        'status' => 'vacant',
    ]);
    $response->assertStatus(200);
    $response->assertJson([
        'unit_number' => 'Unit 102',
    ]);
});
test('units are updated with correct data', function () {
    $this->seed(UnitSeeder::class);
    $user = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($user)->putJson('/api/unit/1', [
        'unit_number' => 'Unit 102',
        'unit_type' => 'apartment',
        'bedrooms' => 2,
        'bathrooms' => 2,
        'square_footage' => 1200.00,
        'rent_amount' => 2000.00,
        'features' => ['balcony', 'in-unit laundry', 'pet-friendly'],
        'status' => 'vacant',
    ]);
    $response->assertStatus(200);
    $this->assertDatabaseHas('units', [
        'unit_number' => 'Unit 102',
    ]);
});
test('units are not updated with invalid data', function () {
    $this->seed(UnitSeeder::class);
    $user = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($user)->putJson('/api/unit/1', [
        'unit_number' => '',
        'unit_type' => 'apartment',
        'bedrooms' => 2,
        'bathrooms' => 2,
        'square_footage' => 1200.00,
        'rent_amount' => 2000.00,
        'features' => ['balcony', 'in-unit laundry', 'pet-friendly'],
        'status' => 'vacant',
    ]);
    $response->assertStatus(422);
});
