<?php

namespace App\Models;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Bank
 *
 * @property int $id
 * @property string $name
 * @property string $number
 * @property string|null $body_color
 * @property string|null $title_color
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Bank newModelQuery()
 * @method static Builder|Bank newQuery()
 * @method static Builder|Bank query()
 * @method static Builder|Bank whereBodyColor($value)
 * @method static Builder|Bank whereCreatedAt($value)
 * @method static Builder|Bank whereId($value)
 * @method static Builder|Bank whereName($value)
 * @method static Builder|Bank whereNumber($value)
 * @method static Builder|Bank whereTitleColor($value)
 * @method static Builder|Bank whereUpdatedAt($value)
 * @mixin Model
 * @mixin Builder
 */
class Bank extends Model
{
    protected $fillable =[
        'name',
        'number',
        'title_color',
        'body_color'
    ];
}
