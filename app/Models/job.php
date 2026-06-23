<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class job extends Model
{
    protected $table = 'job';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'description',
        'min_salary',
        'max_salary',
        'image',
    ];

    public $timestamps = true;
}
