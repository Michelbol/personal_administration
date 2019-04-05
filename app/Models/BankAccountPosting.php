<?php

namespace App\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class BankAccountPosting extends Model
{
    protected $fillable = [
        'document',
        'posting_date',
        'amount',
        'type',
        'type_bank_account_posting_id',
        'account_balance',
        'bank_account_id'
    ];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function typeBankAccountPosting(){
        return $this->belongsTo(TypeBankAccountPosting::class);
    }
}
