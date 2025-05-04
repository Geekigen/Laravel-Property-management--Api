<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'property_id',
        'unit_number',
        'unit_type',
        'bedrooms',
        'bathrooms',
        'square_footage',
        'rent_amount',
        'features',
        'status',
    ];
    protected $casts = [
        'square_footage' => 'decimal:2',
        'rent_amount' => 'decimal:2',
        'features' => 'array',
        'status' => 'string',
    ];
    /**
     * Get the property that owns the unit.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
