<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $fillable = ['event_id','student_id','qr_code','status'];

    public function payments() {
        return $this->hasMany(Payment::class);
    }

    public function photoPackage() {
        return $this->hasOne(EventRegistrationPhotoPackage::class);
    }

    public function seat() {
        return $this->hasOne(EventRegistrationSeat::class);
    }
}
