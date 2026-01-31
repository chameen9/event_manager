<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = [
        'batch_code',
        'program_id',
        'intake_year',
        'intake_month'
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class)
            ->withPivot(['joined_at', 'left_at', 'status'])
            ->withTimestamps();
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
