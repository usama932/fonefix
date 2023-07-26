<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    use HasFactory;
    public function card()
    {
        return $this->belongsTo(IdCard::class,'id_card_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
