<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = ['title','uuid','narrative','event_file', 'start_time', 'end_time', 'user_id','created_by', 'event_category_id'];

    public function event_category(){
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function created_one(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
