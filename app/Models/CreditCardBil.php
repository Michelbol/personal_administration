<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\CreditCardBil
 *
 * @method static Builder|CreditCardBil newModelQuery()
 * @method static Builder|CreditCardBil newQuery()
 * @method static Builder|CreditCardBil query()
 * @mixin \Eloquent
 */
class CreditCardBil extends Model
{
    protected $fillable = [
      'cred_card_id',
      'opening_date',
      'closing_date'
    ];
}
