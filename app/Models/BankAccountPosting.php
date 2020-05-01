<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * App\Models\BankAccountPosting
 *
 * @property int $id
 * @property int|null $income_id
 * @property int|null $expense_id
 * @property string $document
 * @property string $posting_date
 * @property float $amount
 * @property string $type
 * @property float $account_balance
 * @property int $bank_account_id
 * @property int $type_bank_account_posting_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read BankAccount $bankAccount
 * @property-read TypeBankAccountPosting $typeBankAccountPosting
 * @method static Builder|BankAccountPosting newModelQuery()
 * @method static Builder|BankAccountPosting newQuery()
 * @method static Builder|BankAccountPosting query()
 * @method static Builder|BankAccountPosting whereAccountBalance($value)
 * @method static Builder|BankAccountPosting whereAmount($value)
 * @method static Builder|BankAccountPosting whereBankAccountId($value)
 * @method static Builder|BankAccountPosting whereCreatedAt($value)
 * @method static Builder|BankAccountPosting whereDocument($value)
 * @method static Builder|BankAccountPosting whereId($value)
 * @method static Builder|BankAccountPosting wherePostingDate($value)
 * @method static Builder|BankAccountPosting whereType($value)
 * @method static Builder|BankAccountPosting whereTypeBankAccountPostingId($value)
 * @method static Builder|BankAccountPosting whereUpdatedAt($value)
 * @method static Builder|BankAccountPosting whereExpenseId($value)
 * @method static Builder|BankAccountPosting whereIncomeId($value)
 * @mixin Eloquent
 * @mixin Builder
 * @mixin BankAccountPosting
 */
class BankAccountPosting extends Model
{
    protected $fillable = [
        'document',
        'posting_date',
        'amount',
        'type',
        'type_bank_account_posting_id',
        'account_balance',
        'bank_account_id',
        'income_id',
        'expense_id',
    ];

    public function setPostingDateAttribute($value)
    {
        if(isset($value)){
            $this->attributes['posting_date'] = Carbon::createFromFormat('d/m/Y H:i', $value);
        }
    }

    public function setAmountAttribute($value)
    {
        if(isset($value)){
            $this->attributes['amount'] = formatReal($value);
        }
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function typeBankAccountPosting(){
        return $this->belongsTo(TypeBankAccountPosting::class);
    }
}
