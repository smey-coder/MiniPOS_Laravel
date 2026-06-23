<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase_Detail extends Model
{
     protected $table = 'purchase_details';

    protected $primaryKey = 'id';

    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
        'description',
    ];

    public $timestamps = true;

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }
}
