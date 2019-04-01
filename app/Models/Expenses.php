<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $fillable = [
        'id',
        'name',
        'amount',
        'isFixed',
        'due_date',
    ];
}
