<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;
    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }
    public function device()
    {
        return $this->belongsTo(Device::class,'device_id');
    }
}
