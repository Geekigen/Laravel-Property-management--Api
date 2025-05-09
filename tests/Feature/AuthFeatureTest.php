<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

beforeEach(function() {
    if (!Schema::hasTable('users')) {
        Schema::create('leases', function ($table) {
        $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('role', ['admin', 'agent', 'landlord'])->default('landlord');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
});
it('registers a user successfully', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'role' => 'admin',
    ]);

    $response->assertStatus(201)
             ->assertJsonStructure(['user', 'token', 'token_type']);
    $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
});

it('logs in a user successfully', function () {
    $user = User::factory()->create(['password' => bcrypt('password123')]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
             ->assertJsonStructure(['token', 'user']);
});
