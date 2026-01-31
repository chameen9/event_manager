<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['program_id', 'module_order', 'name'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
