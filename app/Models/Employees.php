<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    protected $table = 'employees';
    protected $primarykey = 'id';
    protected $fillable = [
        'name', 'email', 'position', 'salary', 'hire_date', 'image', 
        'status', 'job_id', 'user_id'
    ];

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
