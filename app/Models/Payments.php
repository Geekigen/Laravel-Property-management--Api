<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payments extends Model
{
    use HasFactory;
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
