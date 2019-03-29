<?php

namespace App\Models;

use App\Utilitarios;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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

    static function calcAnnualInterest($bankAccountId){
        return Utilitarios::getFormatReal(DB::table('bank_account_postings')
            ->whereBetween('posting_date', [Carbon::parse('first day of january'),Carbon::now()])
            ->where('type_bank_account_posting_id', 1)
            ->where('type', 'C')
            ->where('bank_account_id', $bankAccountId)
            ->sum('amount'));
    }
    static function calcMonthlyInterest($year, $bankAccountId){
        $interestMonthly = [];
        for($i = 1; $i <=12; $i++){
            $interestMonthly[$i] = DB::table('bank_account_postings')
                ->whereBetween('posting_date', [Carbon::create($year, $i, 1),Carbon::create($year,$i)->endOfMonth()])
                ->where('type_bank_account_posting_id', 1)
                ->where('type', 'C')
                ->where('bank_account_id', $bankAccountId)
                ->sum('amount');
        }
        return $interestMonthly;
    }

    static function calcMonthlyBalance($year, $bankAccountId){
        $balanceMonthly = [];
        for($i = 1; $i <=12; $i++){
            $balance = DB::table('bank_account_postings')
                ->whereBetween('posting_date', [Carbon::create($year, $i, 1),Carbon::create($year,$i)->endOfMonth()])
                ->where('bank_account_id', $bankAccountId)
                ->orderBy('posting_date', 'desc')->first();
            $balanceMonthly[$i] = isset($balance) ? $balance->account_balance/100 : 0;
        }
        return $balanceMonthly;
    }

    static function lastBalance($bankAccountId){
        $accountBalancePosting = DB::table('bank_account_postings')
            ->where('bank_account_id', $bankAccountId)
            ->orderBy('posting_date','desc')
            ->orderBy('id','desc')->first();
        return isset($accountBalancePosting) ? Utilitarios::getFormatReal($accountBalancePosting->account_balance) : '0,00';
    }
}
