<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $appends = ['full_name'];

    protected function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
