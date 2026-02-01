<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoPackage extends Model
{
    public $table = 'event_registrations';
    protected $fillable = ['name','description','price'];

    public function eventPhotoPackages()
    {
        return $this->hasMany(EventPhotoPackage::class);
    }
}
