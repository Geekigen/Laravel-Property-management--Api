<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use App\Models\Lease;
use App\Models\Unit;
use App\Models\Tenants;

uses(RefreshDatabase::class);

beforeEach(function() {
    if (!Schema::hasTable('leases')) {
        Schema::create('leases', function ($table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('rent_amount', 10, 2);
            $table->decimal('security_deposit', 10, 2);
            $table->integer('payment_day');
            $table->enum('lease_type', ['month-to-month', 'fixed-term'])->default('fixed-term');
            $table->enum('status', ['active', 'expired', 'terminated', 'upcoming'])->default('upcoming');
            $table->text('terms')->nullable();
            $table->timestamps();
        });
    }
});

// Unauthenticated users cannot access leases index
test('unauthenticated leases index', function () {
    $response = $this->getJson('/api/lease');
    $response->assertStatus(401);
});

// Authenticated users can access leases index
test('authenticated leases index', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Lease::factory()->count(3)->create();

    $response = $this->getJson('/api/lease');
    $response->assertStatus(200);
    $response->assertJsonCount(3, 'data');
});

// Authenticated users can create a lease
test('authenticated create lease', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $unit = Unit::factory()->create();
    $tenant = Tenants::factory()->create();

    $data = [
        'unit_id' => $unit->id,
        'tenant_id' => $tenant->id,
        'start_date' => now()->startOfDay()->format('Y-m-d H:i:s'),
        'end_date' => now()->addYear()->startOfDay()->format('Y-m-d H:i:s'),
        'rent_amount' => 1500.00,
        'security_deposit' => 1500.00,
        'payment_day' => 1,
        'lease_type' => 'fixed-term',
        'status' => 'active',
        'terms' => 'Sample lease terms.',
    ];

    $response = $this->postJson('/api/lease', $data);
    $response->assertStatus(201);
    $this->assertDatabaseHas('leases', $data);
});

// Authenticated users can update a lease
test('authenticated update lease', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $lease = Lease::factory()->create();

    $data = [
        'rent_amount' => 2000.00,
        'status' => 'active',
        'lease_type' => 'month-to-month',
        'terms' => 'Updated lease terms.',
    ];

    $response = $this->putJson("/api/lease/{$lease->id}", $data);
    $response->assertStatus(200);
    $this->assertDatabaseHas('leases', $data);
});

// Authenticated users can delete a lease
test('authenticated delete lease', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $lease = Lease::factory()->create();

    $response = $this->deleteJson("/api/lease/{$lease->id}");
    $response->assertStatus(200);
    $this->assertDatabaseMissing('leases', ['id' => $lease->id]);
});

// Authenticated users can view a specific lease
test('authenticated view lease', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $lease = Lease::factory()->create();

    $response = $this->getJson("/api/lease/{$lease->id}");
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'id',
        'unit_id',
        'tenant_id',
        'start_date',
        'end_date',
        'rent_amount',
        'security_deposit',
        'payment_day',
        'lease_type',
        'status',
        'terms',
    ]);
});

// Authenticated users receive 404 for non-existent lease
test('authenticated view non-existent lease', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->getJson('/api/lease/999');
    $response->assertStatus(404);
    $response->assertJson([
        'message' => 'No query results for model [App\\Models\\Lease] 999',
    ]);
});
