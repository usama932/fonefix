<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function shop()
    {
        return $this->belongsTo('App\Models\User','shop_id','id');
    }
}
