<?php

namespace App\Models;

use App\Utilitarios;
use App\Scopes\TenantModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\BankAccount
 *
 * @property int $id
 * @property string $name
 * @property int $agency
 * @property int|null $operation
 * @property int|null $digit_agency
 * @property int $number_account
 * @property int|null $digit_account
 * @property int $bank_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $tenant_id
 * @property-read Bank $bank
 * @method static Builder|BankAccount newModelQuery()
 * @method static Builder|BankAccount newQuery()
 * @method static Builder|BankAccount query()
 * @method static Builder|BankAccount whereAgency($value)
 * @method static Builder|BankAccount whereBankId($value)
 * @method static Builder|BankAccount whereCreatedAt($value)
 * @method static Builder|BankAccount whereDigitAccount($value)
 * @method static Builder|BankAccount whereDigitAgency($value)
 * @method static Builder|BankAccount whereId($value)
 * @method static Builder|BankAccount whereName($value)
 * @method static Builder|BankAccount whereNumberAccount($value)
 * @method static Builder|BankAccount whereOperation($value)
 * @method static Builder|BankAccount whereTenantId($value)
 * @method static Builder|BankAccount whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BankAccount extends Model
{
    use TenantModels;

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

    static function calcAnnualInterest($bankAccountId, $year){
        $firstDayYear = Carbon::create($year)->firstOfYear();
        $endOfYear = Carbon::create($year)->lastOfYear();
        return Utilitarios::getFormatReal(DB::table('bank_account_postings')
            ->whereBetween('posting_date', [$firstDayYear,$endOfYear])
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
