<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UsePart
 *
 * @property int $id
 * @property float|null $amount
 * @property int|null $quantity
 * @property string|null $description
 * @property int|null $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UsePart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UsePart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UsePart query()
 * @method static \Illuminate\Database\Eloquent\Builder|UsePart whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsePart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsePart whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsePart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsePart whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsePart whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UsePart whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UsePart extends Model
{
    use HasFactory;
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function job()
    {
        return $this->belongsTo(Job::class,'job_id');
    }
}
