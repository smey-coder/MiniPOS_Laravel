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
        'address',
        'description',
    ];
    protected $tiemestamps = true;

    public function purchase(){
        return $this->hasMany(Purchase::class, 'purchase_id', 'id');
    }
}
