<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsSetting extends Model
{
    use HasFactory;
     public function shop(){
        return $this->hasOne(User::class,'user_id','id');
     }
}
