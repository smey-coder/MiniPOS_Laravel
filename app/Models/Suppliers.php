<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    protected $table = 'supplier';
    protected $primarykey = 'id';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address'
    ];

    protected $tiemestamps = true;
}
