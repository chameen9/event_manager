<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPhotoPackage extends Model
{
    public $table = 'event_registration_photo_packages';
    protected $fillable = ['event_registration_id','photo_package_id','quantity','price'];

    public function photoPackage()
    {
        return $this->belongsTo(PhotoPackage::class);
    }
}
