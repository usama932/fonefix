<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compatible extends Model
{
    use HasFactory;
    public function devices(){
        return $this->hasMany(DeviceCompatible::class,'compatible_id');
    }
    public function devicesApi(){
        return $this->hasMany(DeviceCompatible::class,'compatible_id')
            ->leftJoin('devices', 'device_compatibles.device_id', '=', 'devices.id')
            ->select(
                'device_compatibles.*',
                'devices.name as device_name'
            );
    }
    public function type()
    {
        return $this->belongsTo(Type::class,'type_id');
    }
    public function shop()
    {
        return $this->belongsTo(User::class,'shop_id','id');
    }
}
