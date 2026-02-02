<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventData extends Model
{
    public $table = 'event_data';
    protected $fillable = ['event_id','max_seat_count','max_additional_seat_count','max_shuttle_seat_count'];
}
