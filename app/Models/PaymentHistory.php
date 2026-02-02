<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'payment_history';

    protected $fillable = [
        'event_registration_id',
        'payment_id',
        'amount',
        'method',
        'remarks',
        'handled_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function eventRegistration()
    {
        return $this->belongsTo(EventRegistration::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
