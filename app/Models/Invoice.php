<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    public function parts(){
        return $this->hasMany(UsePart::class,'invoice_id');
    }
    public function job()
    {
        return $this->belongsTo(Job::class,'job_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class,'customer_id');
    }

    public function shop()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
