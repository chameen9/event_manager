<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['student_id','full_name','email','phone'];

    public function registrations() {
        return $this->hasMany(Registration::class);
    }

    public function eventRegistrations() {
        return $this->hasMany(EventRegistration::class);
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class)
            ->withPivot(['joined_at', 'left_at', 'status'])
            ->withTimestamps();
    }

    public function activeBatch()
    {
        return $this->belongsToMany(Batch::class)
            ->wherePivot('status', 'active');
    }
}
