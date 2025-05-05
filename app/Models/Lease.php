<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lease extends Model
{
    use HasFactory;
    protected $fillable = [
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
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'rent_amount' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'payment_day' => 'integer',
        'terms' => 'string',
    ];
    /**
     * Get the unit that owns the lease.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    /**
     * Get the tenant that owns the lease.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenants::class);
    }
}
