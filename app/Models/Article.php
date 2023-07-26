<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;
    protected $data = ['deleted_at'];

    protected $fillable = [
        'title', 'case_number', 'slug', 'image', 'content', 'user_id', 'category_id', 'priority'
    ];

    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function categories() {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function images(){
        return $this->hasMany(ArticleImage::class,'stamp_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }
}
