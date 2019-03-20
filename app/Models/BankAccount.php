<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'name',
        'agency',
        'digit_agency',
        'number_account',
        'digit_account',
        'operation',
        'bank_id'
    ];


    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
