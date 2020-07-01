<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\CreditCardBillPosting
 *
 * @property int $id
 * @property int $bill_id
 * @property float $amount
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CreditCardBillPosting newModelQuery()
 * @method static Builder|CreditCardBillPosting newQuery()
 * @method static Builder|CreditCardBillPosting query()
 * @method static Builder|CreditCardBillPosting whereAmount($value)
 * @method static Builder|CreditCardBillPosting whereBillId($value)
 * @method static Builder|CreditCardBillPosting whereCreatedAt($value)
 * @method static Builder|CreditCardBillPosting whereDescription($value)
 * @method static Builder|CreditCardBillPosting whereId($value)
 * @method static Builder|CreditCardBillPosting whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CreditCardBillPosting extends Model
{
    protected $fillable =[
        'bill_id',
        'amount',
        'description'
    ];
}
