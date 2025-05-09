<?php

use App\Models\User;
use Database\Seeders\UnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

beforeEach(function() {
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

test('units created', function () {
    $this->seed(UnitSeeder::class);
    $this->assertDatabaseHas('units', []);
});
test('units created with correct data', function () {
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
// if index is not authenticated
test('unauthenticated units index', function () {
    $this->seed(UnitSeeder::class);
    $response = $this->getJson('/api/unit');
    $response->assertStatus(401);
});
// authenticated units index
test('authenticated units index', function () {
    $this->seed(UnitSeeder::class);
    $user = User::factory()->create();
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
// test the show method
test('unauthenticated units show', function () {
    $this->seed(UnitSeeder::class);
    $unit = \App\Models\Unit::first();
    $response = $this->getJson('/api/unit/' . $unit->id);
    $response->assertStatus(401);
});
// authenticated units show
test('authenticated units show', function () {
    $this->seed(UnitSeeder::class);
    $user = User::factory()->create();
    $response = $this->actingAs($user)->getJson('/api/unit/' . 1);
    $response->assertStatus(200);
});
