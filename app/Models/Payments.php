<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $fillable = [
        'lease_id',
        'amount',
        'due_date',
        'payment_date',
        'payment_method',
        'transaction_id',
        'status',
        'notes',
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'date',
        'status' => 'string',
        'notes' => 'string',
    ];
    /**
     * Get the lease that owns the payment.
     */
    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }
}
