<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
