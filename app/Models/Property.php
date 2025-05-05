<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;
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
