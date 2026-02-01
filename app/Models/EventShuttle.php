<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventShuttle extends Model
{
    public $table = 'event_registration_shuttle_seats';
    protected $fillable = ['event_registration_id','shuttle_seat_count','price'];
}
