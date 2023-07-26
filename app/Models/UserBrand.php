<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBrand extends Model
{
    use HasFactory;
    public function devices(){
        return $this->hasMany(UserDevice::class,'user_brand_id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class,'device_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }

}
