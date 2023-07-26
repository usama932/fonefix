<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketImage extends Model
{
    use HasFactory;
    public function ticket(){
        return $this->belongsTo(TicketImage::class,'ticket_id');
    }
}
