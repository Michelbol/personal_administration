<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    protected $fillable = [
      'name',
      'limit',
      'default_closing_date',
      'bank_id'
    ];
}
