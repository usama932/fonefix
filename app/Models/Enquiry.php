<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Enquiry extends Model
{
    use HasFactory;
    public function brands(){
        return $this->hasMany(EnquiryBrand::class,'enquiry_id');
    }
    public function brand(){
        return $this->hasOne(EnquiryBrand::class,'enquiry_id');
    }
    public function shop()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
