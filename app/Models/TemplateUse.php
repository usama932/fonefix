<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateUse extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function template(){
        return $this->belongsTo(SmsTempalate::class,'template_id','id');
    }
}
