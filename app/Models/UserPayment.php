<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPayment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function payment(){
        return $this->hasOne(App\Models\UserPayments::class,'user_id','id');
    }
}
