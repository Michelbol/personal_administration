<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CreditCardBil
 *
 * @method static Builder|CreditCardBil newModelQuery()
 * @method static Builder|CreditCardBil newQuery()
 * @method static Builder|CreditCardBil query()
 * @mixin Eloquent
 */
class CreditCardBil extends Model
{
    protected $fillable = [
      'cred_card_id',
      'opening_date',
      'closing_date'
    ];
}
