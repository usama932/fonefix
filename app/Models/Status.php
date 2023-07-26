<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    public function jobs(){
        return $this->hasOne(Job::class,'status_id','id');
    }

    public function shop(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function used(){
        return $this->hasOne(Statususe::class,'status_id','id');
    }
}
