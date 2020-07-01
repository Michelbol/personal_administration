<?php

namespace App\Models;

use App\Scopes\TenantModels;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
 * @mixin Eloquent
 * @mixin Builder
 * @mixin Model
 * @mixin BankAccount
 * @property-read Collection|BankAccountPosting[] $bankAccountPostings
 * @property-read int|null $bank_account_postings_count
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

    public function bankAccountPostings()
    {
        return $this->hasMany(BankAccountPosting::class);
    }
}
