<?php
use App\Models\Lease;
use App\Models\Unit;
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

it('has a unit relationship', function () {
    $unit = Unit::factory()->create();
    $lease = Lease::factory()->create(['unit_id' => $unit->id]);

    expect($lease->unit)->toBeInstanceOf(Unit::class);
});
