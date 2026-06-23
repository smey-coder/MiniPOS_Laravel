<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'customer_type_id',
        'name',
        'phone',
        'email',
        'address',
        'city',
    ];
    public $timestamps = true;
    
    public function customer_type()
    {
        return $this->belongsTo(Customer_Type::class, 'customer_type_id', 'id');
    }
}
