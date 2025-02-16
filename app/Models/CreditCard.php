<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\CreditCard
 *
 * @property int $id
 * @property string $name
 * @property float|null $limit
 * @property int|null $default_closing_date
 * @property int|null $bank_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CreditCard newModelQuery()
 * @method static Builder|CreditCard newQuery()
 * @method static Builder|CreditCard query()
 * @method static Builder|CreditCard whereBankId($value)
 * @method static Builder|CreditCard whereCreatedAt($value)
 * @method static Builder|CreditCard whereDefaultClosingDate($value)
 * @method static Builder|CreditCard whereId($value)
 * @method static Builder|CreditCard whereLimit($value)
 * @method static Builder|CreditCard whereName($value)
 * @method static Builder|CreditCard whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CreditCard extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'limit',
      'default_closing_date',
      'bank_id'
    ];
}
