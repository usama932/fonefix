<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    // public function folder() {
    //     return $this->belongsTo(User::class,'folder_id','id');
    // }
    public function folder() {
        return $this->belongsTo(Folder::class,'folder_id','id');
    }
}
