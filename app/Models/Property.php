<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'property_type',
        'year_built',
        'active',
    ];
    protected $casts = [
        'active' => 'boolean',
        'year_built' => 'integer',
    ];
    /**
     * Get the user that owns the property.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
