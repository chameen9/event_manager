<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['event_registration_id','amount','paid','status','paid_at'];

    protected $casts = [
        'paid'    => 'float',
        'paid_at' => 'datetime',
        'amount'  => 'float',
    ];

    public function eventRegistration()
    {
        return $this->belongsTo(EventRegistration::class);
    }
}
