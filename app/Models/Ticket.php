<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    // protected $appends = ['latest_status'];
    protected $appends = ['latest_status', 'details_url'];

    protected $guarded = [];

    public function tutor() {
        return $this->belongsTo(User::class, 'assigned_tutor_id', 'id');
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function ticketStatuses() {
        return $this->belongsToMany(TicketStatus::class);
    }

    public function getLatestStatusAttribute() {
        return $this->ticketStatuses->reverse()->first();
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function typeOfMachine() {
        return $this->belongsTo(TypeOfMachine::class);
    }

    public function operatingSystem() {
        return $this->belongsTo(OperatingSystem::class);
    }

    public function priority() {
        return $this->belongsTo(Priority::class);
    }

    public function program() {
        return $this->belongsTo(Program::class);
    }

    public function getDetailsUrlAttribute() {
        return '/api/tickets/' . $this->id;
    }
}
