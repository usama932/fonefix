<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $fillable = ['vehicle_no','Registration_no','year','image', 'make', 'model', 'notes', 'active'];

    public function tickets() {
        return $this->hasMany(FleetTicket::class,'vehicle_id','id');
    }
}
