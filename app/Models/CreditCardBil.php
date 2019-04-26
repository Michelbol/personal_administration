<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditCardBil extends Model
{
    protected $fillable = [
      'cred_card_id',
      'opening_date',
      'closing_date'
    ];
}
