<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchStudent extends Model
{
    public $table = 'batch_student';
    protected $fillable = ['student_id','batch_id','joined_at','left_at','status'];
}
