<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = ['name','duration_months'];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}
