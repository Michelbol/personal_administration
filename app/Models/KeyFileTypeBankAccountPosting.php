<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\KeyFileTypeBankAccountPosting
 *
 * @property int $id
 * @property string $key_file
 * @property int|null $expense_id
 * @property int|null $income_id
 * @property int $type_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|KeyFileTypeBankAccountPosting newModelQuery()
 * @method static Builder|KeyFileTypeBankAccountPosting newQuery()
 * @method static Builder|KeyFileTypeBankAccountPosting query()
 * @method static Builder|KeyFileTypeBankAccountPosting whereCreatedAt($value)
 * @method static Builder|KeyFileTypeBankAccountPosting whereExpenseId($value)
 * @method static Builder|KeyFileTypeBankAccountPosting whereId($value)
 * @method static Builder|KeyFileTypeBankAccountPosting whereIncomeId($value)
 * @method static Builder|KeyFileTypeBankAccountPosting whereKeyFile($value)
 * @method static Builder|KeyFileTypeBankAccountPosting whereUpdatedAt($value)
 * @method static Builder|KeyFileTypeBankAccountPosting whereTypeId($value)
 * @property-read Expenses|null $expense
 * @property-read Income|null $income
 * @mixin Builder
 * @mixin Model
 */
class KeyFileTypeBankAccountPosting extends Model
{
    protected $fillable = [
        'type_id',
        'key_file',
        'expense_id',
        'income_id',
    ];

    public function expense(){
        return $this->belongsTo(Expenses::class);
    }
    public function income(){
        return $this->belongsTo(Income::class);
    }
}
