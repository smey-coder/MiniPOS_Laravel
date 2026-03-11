<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    //
    protected $table = 'role';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];
    protected $tiemestamps = true;
}
