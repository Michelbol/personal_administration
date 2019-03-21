<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'SEGURADORA'    => 9
    ];
    protected $fillable = [
        'name'
    ];


    function getTypeBankAccountPosting($index){
        return isset($this->historicos[$index]) ? $this->historicos[$index] : 0;
    }
}
