<?php

namespace App\Models;

use App\Scopes\TenantModels;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Expenses
 *
 * @property int $id
 * @property string $name
 * @property int $isFixed
 * @property float $amount
 * @property int|null $due_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $tenant_id
 * @method static Builder|Expenses newModelQuery()
 * @method static Builder|Expenses newQuery()
 * @method static Builder|Expenses query()
 * @method static Builder|Expenses whereAmount($value)
 * @method static Builder|Expenses whereCreatedAt($value)
 * @method static Builder|Expenses whereDueDate($value)
 * @method static Builder|Expenses whereId($value)
 * @method static Builder|Expenses whereIsFixed($value)
 * @method static Builder|Expenses whereName($value)
 * @method static Builder|Expenses whereTenantId($value)
 * @method static Builder|Expenses whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Expenses extends Model
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
