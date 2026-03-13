<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'clientType',
        'name',
        'phone',
        'email',
        'address',
        'city',
        'discount'
    ];

    protected $casts = [
        'discount' => 'decimal:2'
    ];
}
