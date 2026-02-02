<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $fillable = ['event_id','student_id','qr_code','status'];

    public function payment() {
        return $this->hasOne(Payment::class);
    }

    public function photoPackages()
    {
        return $this->hasMany(EventPhotoPackage::class, 'event_registration_id');
    }

    public function seat() {
        return $this->hasOne(EventSeat::class);
    }

    public function shuttleSeats()
    {
        return $this->hasOne(EventShuttle::class);
    }

    public function logs()
    {
        return $this->hasMany(EventLog::class)->orderBy('id', 'desc');
    }

    public function paymentHistories()
    {
        return $this->hasMany(PaymentHistory::class);
    }
}
