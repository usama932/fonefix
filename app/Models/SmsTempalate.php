<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTempalate extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sms(){
        return $this->hasOne(SmsSetting::class,'template_id','id');
    }
    public function used(){
        return $this->hasOne(TemplateUse::class,'template_id','id');
    }
}
