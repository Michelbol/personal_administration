<?php

namespace App\BankAccountPosting;

use App\Models\BankAccount;
use App\Models\BankAccountPosting;
use App\Models\Enum\TypeBankAccountPostingEnum;
use App\Ofx;
use Carbon\Carbon;

class BankAccountPostingOfxNubank implements BankAccountPostingOfx
{

    public function saveBankAccountPostingFromOfx(Ofx $ofx, BankAccount $bankAccount): void
    {
        foreach ($ofx->bankTranList as $transaction){
            $bankAccountPosting = new BankAccountPosting();
            $bankAccountPosting->posting_date = Carbon::createFromFormat("YmdHis", substr($transaction->DTPOSTED, 0, 14))->format('d/m/Y H:i');
            $bankAccountPosting->bank_account_id = $bankAccount->id;
            $bankAccountPosting->document = $transaction->FITID;
            $bankAccountPosting->amount = ((float)$transaction->TRNAMT < 0) ? -((float)$transaction->TRNAMT) : (float)$transaction->TRNAMT;
            $bankAccountPosting->type = (string)$transaction->TRNTYPE === Ofx::ofxCredit ? TypeBankAccountPostingEnum::CREDIT : TypeBankAccountPostingEnum::DEBIT;
            $array[] = $bankAccountPosting->toArray();

        }
        echo $array;
    }

    public function retrieveTypeBankAccountPostingNotSaved(): array
    {
        return [];
    }
}
