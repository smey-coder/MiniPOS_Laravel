<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice_Details extends Model
{
    protected $table = 'invoice_details';
    protected $primarykey = 'id';
    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'price',
        'total'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoices::class, 'invoice_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }
}