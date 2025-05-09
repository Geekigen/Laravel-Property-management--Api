<?php

use App\Models\Tenants;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Unauthenticated users cannot access tenants index
test('unauthenticated tenants index', function () {
    $response = $this->getJson('/api/tenant');
    $response->assertStatus(401);
});

// Authenticated users can access tenants index
test('authenticated tenants index', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Tenants::factory()->count(3)->create();

    $response = $this->getJson('/api/tenant');
    $response->assertStatus(200);
    $response->assertJsonCount(3, 'data');
});

// Authenticated users can create a tenant
test('authenticated create tenant', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $data = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'date_of_birth' => '1990-01-01',
        'id_number' => 'ID123456',
        'status' => 'active',
    ];

    $response = $this->postJson('/api/tenant', $data);
    $response->assertStatus(201);
    $this->assertDatabaseHas('tenants', $data);
});

// Authenticated users can update a tenant
test('authenticated update tenant', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $tenant = Tenants::factory()->create();

    $data = [
        'name' => 'Jane Doe',
        'email' => 'janedoe@example.com',
        'date_of_birth' => '1985-05-15',
        'id_number' => 'ID654321',
        'status' => 'inactive',
    ];

    $response = $this->putJson("/api/tenant/{$tenant->id}", $data);
    $response->assertStatus(200);
    $this->assertDatabaseHas('tenants', $data);
});

// Authenticated users can delete a tenant
test('authenticated delete tenant', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $tenant = Tenants::factory()->create();

    $response = $this->deleteJson("/api/tenant/{$tenant->id}");
    $response->assertStatus(200);
    $this->assertDatabaseMissing('tenants', ['id' => $tenant->id]);
});

// Authenticated users can view a specific tenant
test('authenticated view tenant', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $tenant = Tenants::factory()->create();

    $response = $this->getJson("/api/tenant/{$tenant->id}");
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'id',
        'name',
        'email',
        'date_of_birth',
        'id_number',
        'status',
    ]);
});

// Authenticated users receive 404 for non-existent tenant
test('authenticated view non-existent tenant', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->getJson('/api/tenant/999');
    $response->assertStatus(404);
    $response->assertJson([
        'message' => 'Tenant not found',
    ]);
});
