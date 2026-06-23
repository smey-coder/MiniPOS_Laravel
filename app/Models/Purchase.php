<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchases';

    protected $primaryKey = 'id';

    protected $fillable = [
        'supplier_id',
        'employee_id',
        'purchase_code',
        'purchase_date',
        'total_amount',
        'purchase_status',
        'note',
    ];

    public $timestamps = true;

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'supplier_id', 'id');
    }
    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }
    public function purchaseDetails()
    {
        return $this->hasMany(
            Purchase_Detail::class,
            'purchase_id',
            'id'
        );
    }
}
