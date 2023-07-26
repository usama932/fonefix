<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sms(){
        return $this->hasOne(WhatsappSetting::class,'template_id','id');
    }
    public function used(){
        return $this->hasOne(WTemplateUse::class,'template_id','id');
    }
}
