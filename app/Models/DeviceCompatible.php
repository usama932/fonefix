<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceCompatible extends Model
{
    use HasFactory;
    public function device()
    {
        return $this->belongsTo(Device::class,'device_id');
    }
    public function compatible()
    {
        return $this->belongsTo(Device::class,'compatible_id');
    }
}
