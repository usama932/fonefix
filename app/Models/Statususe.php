<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statususe extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function template(){
        return $this->belongsTo(Status::class,'status_id','id');
    }
}
