<?php

namespace App\Models;

use App\Scopes\TenantModels;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Income
 *
 * @property int $id
 * @property string $name
 * @property int $isFixed
 * @property float $amount
 * @property int|null $due_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $tenant_id
 * @method static Builder|Income newModelQuery()
 * @method static Builder|Income newQuery()
 * @method static Builder|Income query()
 * @method static Builder|Income whereAmount($value)
 * @method static Builder|Income whereCreatedAt($value)
 * @method static Builder|Income whereDueDate($value)
 * @method static Builder|Income whereId($value)
 * @method static Builder|Income whereIsFixed($value)
 * @method static Builder|Income whereName($value)
 * @method static Builder|Income whereTenantId($value)
 * @method static Builder|Income whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Income extends Model
{
    use TenantModels;

    protected $fillable = [
        'id',
        'name',
        'amount',
        'isFixed',
        'due_date',
    ];
}
