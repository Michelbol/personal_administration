<?php

namespace App\Models;

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
 * @mixin \Eloquent
 */
class TypeBankAccountPosting extends Model
{
    protected $historicos =[
        'REM BASICA'    => 1,
        'CRED JUROS'    => 1,
        'TEDSALARIO'    => 7,
        'PAG FONE'      => 5,
        'PAG BOLETO'    => 5,
        'TEV MESM T'    => 3,
        'DOC ELET'      => 3,
        'DOC ELET E'    => 3,
        'TARIFA DOC'    => 3,
        'COMPRA ELO'    => 6,
        'CRED TEV'      => 4,
        'CRED TED'      => 4,
        'CAN DB ACC'    => 1,
        'SALAR PRIV'    => 1,
        'ES DB ACC'     => 1,
        'SAQUE ATM'     => 8,
        'SAQ CARTAO'    => 8,
        'SEGURADORA'    => 9,
        'CRED FGTS'     => 10,
        'ABONO PIS'     => 11,
        'DP DINH AG'    => 4,
    ];
    protected $fillable = [
        'name'
    ];


    function getTypeBankAccountPosting($index){
        return isset($this->historicos[$index]) ? $this->historicos[$index] : 0;
    }
}
