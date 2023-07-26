<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WTemplateUse extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function template(){
        return $this->belongsTo(WhatsappTemplate::class,'template_id','id');
    }
}
