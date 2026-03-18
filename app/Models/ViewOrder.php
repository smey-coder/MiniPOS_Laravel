<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewOrder extends Model
{
    protected $table = 'view_orders';
    public $timestamps = false; // important for VIEW

    protected $fillable = [
        'id',
        'customer_name',
        'total',
        'created_at'
    ];
}
