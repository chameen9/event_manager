<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoPackage extends Model
{
    public $table = 'photo_packages';
    protected $fillable = ['name','description','price'];

    public function eventPhotoPackages()
    {
        return $this->hasMany(EventPhotoPackage::class);
    }
}
