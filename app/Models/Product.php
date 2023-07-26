<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function devices(){
        return $this->belongsToMany('App\Models\Device');
    }
    public function images(){
        return $this->hasMany(ProductImage::class,'product_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }
    public function shop()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
