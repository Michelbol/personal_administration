<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditCardBillPosting extends Model
{
    protected $fillable =[
        'bill_id',
        'amount',
        'description'
    ];
}
