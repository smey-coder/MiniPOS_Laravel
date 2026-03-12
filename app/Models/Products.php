<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'barcode',
        'product_type_id',
        'supplier_id',
        'description',
        'cost_price',
        'sell_price',
        'quantity',
        'image'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public $timestamps = true;

    public function productType()
    {
        return $this->belongsTo(Product_Types::class, 'product_type_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'supplier_id', 'id');
    }
}