<?php

use App\Models\User;
use Database\Seeders\PaymentsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

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

test('payments created', function () {
    $this->seed(PaymentsSeeder::class);
    $this->assertDatabaseCount('payments', 2);
});

test('payments created with correct data', function () {
    $this->seed(PaymentsSeeder::class);
    $this->assertDatabaseHas('payments', [
        'lease_id' => 1,
        'amount' => 2500.00,
        'due_date' => '2025-02-01',
        'payment_date' => '2025-02-01',
        'payment_method' => 'credit_card',
        'transaction_id' => 'TXN-98765432',
        'status' => 'paid',
        'notes' => 'First month rent paid on time.',
    ]);
});
test('overdue payments created', function () {
    $this->seed(PaymentsSeeder::class);
    $this->assertDatabaseCount('payments', 2);
    $this->assertDatabaseHas('payments', [
        'status' => 'due',
    ]);
});
// test the controller  logic
// index
test('unauthenticated payments index', function () {
    $this->seed(PaymentsSeeder::class);
    $response = $this->getJson('/api/payment');
    $response->assertStatus(401);
});
// authenticated payments index
test('authenticated payments index', function () {
    $this->seed(PaymentsSeeder::class);
    $user = User::factory()->create();
    $response = $this->actingAs($user)->getJson('/api/payment');
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'lease_id',
                'amount',
                'due_date',
                'payment_date',
                'payment_method',
                'transaction_id',
                'status',
                'notes',
            ],
        ],
    ]);
});

// delete logic test
test('unauthenticated payments delete', function () {
    $this->seed(PaymentsSeeder::class);
    $response = $this->deleteJson('/api/payment/1');
    $response->assertStatus(401);
});
// authenticated payments delete
test('authenticated payments delete', function () {
    $this->seed(PaymentsSeeder::class);
    $user = User::factory()->create();
    $response = $this->actingAs($user)->deleteJson('/api/payment/1');
    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Payment deleted successfully',
    ]);
});

// show logic test
test('unauthenticated payments show', function () {
    $this->seed(PaymentsSeeder::class);
    $response = $this->getJson('/api/payment/1');
    $response->assertStatus(401);
});
// authenticated payments show
test('authenticated payments show', function () {
    $this->seed(PaymentsSeeder::class);
    $user = User::factory()->create();
    $response = $this->actingAs($user)->getJson('/api/payment/1');
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'id',
        'lease_id',
        'amount',
        'due_date',
        'payment_date',
        'payment_method',
        'transaction_id',
        'status',
        'notes',
    ]);
});
 test('authenticated payments show not found', function () {
    $this->seed(PaymentsSeeder::class);
    $user = User::factory()->create();
    $response = $this->actingAs($user)->getJson('/api/payment/999');
    $response->assertStatus(404);
    $response->assertJson([
        'message' => 'No query results for model [App\\Models\\Payments] 999',
    ]);
});
// update logic test
test('unauthenticated payments update', function () {
    $this->seed(PaymentsSeeder::class);
    $response = $this->putJson('/api/payment/1', [
        'amount' => 3000.00,
        'status' => 'paid',
    ]);
    $response->assertStatus(401);
});
// authenticated payments update
test('authenticated payments update', function () {
    $this->seed(PaymentsSeeder::class);
    $user = User::factory()->create();
    $response = $this->actingAs($user)->putJson('/api/payment/1', [
        'amount' => 3000.00,
        'status' => 'paid',
    ]);
    $response->assertStatus(200);
    $response->assertJsonFragment([
        'id' => 1,
        'amount' => '3000.00',
        'status' => 'paid',
    ]);
    $this->assertDatabaseHas('payments', [
        'id' => 1,
        'amount' => 3000.00,
        'status' => 'paid',
    ]);
});
// test for not found
test('authenticated payments update not found', function () {
    $this->seed(PaymentsSeeder::class);
    $user = User::factory()->create();
    $response = $this->actingAs($user)->putJson('/api/payment/999', [
        'amount' => 3000.00,
        'status' => 'paid',
    ]);
    $response->assertStatus(404);
    $response->assertJson([
        'message' => 'No query results for model [App\\Models\\Payments] 999',
    ]);
});
// test for validation
test('authenticated payments update validation', function () {
    $this->seed(PaymentsSeeder::class);
    $user = User::factory()->create();
    $response = $this->actingAs($user)->putJson('/api/payment/1', [
        'amount' => 200.00,
        'status' => 'paid',
    ]);
    $response->assertStatus(200);
});
test('authenticated payments update validation for not found', function () {
    $this->seed(PaymentsSeeder::class);
    $user = User::factory()->create();
    $response = $this->actingAs($user)->putJson('/api/payment/999', [
        'amount' => 134,
        'status' => 'paid',
    ]);
    $response->assertStatus(404);
});
