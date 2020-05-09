<?php

namespace App\Models;

use Eloquent;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\TypeBankAccountPosting
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|TypeBankAccountPosting newModelQuery()
 * @method static Builder|TypeBankAccountPosting newQuery()
 * @method static Builder|TypeBankAccountPosting query()
 * @method static Builder|TypeBankAccountPosting whereCreatedAt($value)
 * @method static Builder|TypeBankAccountPosting whereId($value)
 * @method static Builder|TypeBankAccountPosting whereName($value)
 * @method static Builder|TypeBankAccountPosting whereUpdatedAt($value)
 * @mixin Builder
 * @mixin Model
 * @mixin Eloquent
 * @mixin TypeBankAccountPosting
 */
class TypeBankAccountPosting extends Model
{
    protected $fillable = [
        'name'
    ];

    /**
     * @param $index
     * @return KeyFileTypeBankAccountPosting|Builder|Model|int|object|null
     */
    function getType($index){
        $keyFileTypeBankAccountPosting = KeyFileTypeBankAccountPosting::whereKeyFile($index)->first();
        return isset($keyFileTypeBankAccountPosting) ? $keyFileTypeBankAccountPosting : 0;
    }
}
