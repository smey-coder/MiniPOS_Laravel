<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer_Type extends Model
{
    protected $table = 'customer_types';

    protected $fillable = [
        'name',
        'desription',
        'discount_rate',
    ];

    protected $casts = [
        'discount_rate' => 'decimal:2',
    ];

    public $timestamps = true;

    public function customers()
    {
        return $this->hasMany(Customers::class,'customer_type_id','id');
    }


}
