<?php

namespace App\BankAccountPosting;

use App\Models\Bank;
use App\Models\BankAccount;

class BankAccountPostingOfxResolver
{

    public static function resolve(Bank $bank): BankAccountPostingOfx
    {
        switch ($bank->number){
            case Bank::CAIXA_NUMBER:
                return app(BankAccountPostingOfxCaixa::class);
            case Bank::NUBANK_NUMBER:
                return app(BankAccountPostingOfxNubank::class);
            default:
                return app(BankAccountPostingOfxCaixa::class);
        }
    }
}
