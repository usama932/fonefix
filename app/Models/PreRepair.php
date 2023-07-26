<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PreRepair
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $device_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PreRepair newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PreRepair newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PreRepair query()
 * @method static \Illuminate\Database\Eloquent\Builder|PreRepair whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreRepair whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreRepair whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreRepair whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreRepair whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PreRepair extends Model
{
    use HasFactory;
}
