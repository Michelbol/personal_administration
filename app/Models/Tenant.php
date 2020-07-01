<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Tenant
 *
 * @property int $id
 * @property string $name
 * @property string $sub_domain
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Tenant newModelQuery()
 * @method static Builder|Tenant newQuery()
 * @method static Builder|Tenant query()
 * @method static Builder|Tenant whereCreatedAt($value)
 * @method static Builder|Tenant whereId($value)
 * @method static Builder|Tenant whereName($value)
 * @method static Builder|Tenant whereSubDomain($value)
 * @method static Builder|Tenant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tenant extends Model
{
    protected $fillable = [
        'name',
        'sub_domain'
    ];
}
