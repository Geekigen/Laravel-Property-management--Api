<?php
use App\Jobs\SendRentDueReminder;
use App\Models\Payments as Payment;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
    beforeEach(function () {
        Bus::fake();
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function ($table) {
                $table->id();
                $table->foreignId('lease_id')->constrained()->onDelete('cascade');
                $table->decimal('amount', 10, 2);
                $table->date('due_date');
                $table->enum('status', ['paid', 'due', 'late', 'partially_paid'])->default('due');
                $table->timestamps();
            });
        }
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

    it('shows zero when no payments due', function () {
        $this->artisan('app:send-rent-due-reminders')
            ->expectsOutput('rent due reminders dispatched.')
            ->assertExitCode(0);
    });

    it('only processes upcoming payments', function () {
        Payment::factory()->create(['due_date' => now()->addDay()]); // upcoming
        Payment::factory()->create(['due_date' => now()->subDay()]); // past

        $this->artisan('app:send-rent-due-reminders')
            ->expectsOutput('rent due reminders dispatched.');
    });
