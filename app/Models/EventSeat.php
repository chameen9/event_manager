<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSeat extends Model
{
    public $table = 'event_registration_seats';
    protected $fillable = ['event_registration_id','seat_number','additional_seat_count','price'];
}
