<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedulePage extends Model
{
    use HasFactory;

    protected $fillable = ['schedule_url'];

    public function user()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}
