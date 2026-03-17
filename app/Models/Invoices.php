<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    protected $table = 'invoices';
    protected $primarykey = 'id';
    protected $fillable = [
        'customer_id',
        'employee_id',
        'invoice_status',
        'memo'
    ];

    //Invoice -> Invoice Details
    public function details()
    {
        return $this->hasMany(Invoice_Details::class,'invoice_id','id');
    }

    // Invoice -> Customer
    public function customer()
    {
        return $this->belongsTo(Customers::class,'customer_id','id');
    }

    // Invoice -> Employee
    public function employee()
    {
        return $this->belongsTo(Employees::class,'employee_id','id');
    }
}