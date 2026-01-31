<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleCompletion extends Model
{
    protected $fillable = ['registration_id','module_id','completed_at'];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
