<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenants extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'date_of_birth',
        'id_number',
        'status',
    ];
}
