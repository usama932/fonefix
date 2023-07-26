<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetTicket extends Model
{
    use HasFactory;
    protected $fillable = [
        'remarks', 'status', 'vehicle_mileage', 'vehicle_id', 'complaint','vehicle_mileage'
    ];

    public function vehicles() {
        return $this->belongsTo(Vehicle::class,'vehicle_id');
    }
    public function complaints() {
        return $this->belongsTo(FleetComplaint::class,'complaint_id');
    }
    public function images(){
        return $this->hasMany(TicketImage::class,'ticket_id');
    }
}
