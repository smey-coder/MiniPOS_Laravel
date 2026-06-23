<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_Types extends Model
{
    protected $table = 'product_type';

    protected $fillable = [
        'name',
        'description',
    ];
    public $timestamps = true;
}
