<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappSetting extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function template(){
        return $this->belongsTo(WhatsappTempalate::class,'template_id','id');
    }
}
