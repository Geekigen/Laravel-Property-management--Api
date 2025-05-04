<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenants extends Model
{
    protected $fillable = [
        'name',
        'email',
        'date_of_birth',
        'id_number',
        'status',
    ];
}
