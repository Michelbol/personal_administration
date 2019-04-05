<?php

namespace App\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

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
