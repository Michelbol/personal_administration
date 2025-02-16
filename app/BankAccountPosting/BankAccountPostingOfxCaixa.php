<?php

namespace App\BankAccountPosting;

use App\Models\BankAccount;
use App\Models\BankAccountPosting;
use App\Models\Enum\TypeBankAccountPostingEnum;
use App\Models\KeyFileTypeBankAccountPosting;
use App\Ofx;
use App\Repositories\TypeBankAccountPostingRepository;
use App\Services\BankAccountPostingService;
use Carbon\Carbon;

class BankAccountPostingOfxCaixa
{
    public function __construct(
        private BankAccountPostingService $bankAccountPostingService,
        private TypeBankAccountPostingRepository $typeBankAccountPostingRepo,
    ){}

    public function mountBankAccountPostingOfx($transactions, BankAccount $bankAccount, KeyFileTypeBankAccountPosting $keyFileTypeBankAccountPosting)
    {
        $bankAccountPosting = new BankAccountPosting();
        $bankAccountPosting->type_bank_account_posting_id = $keyFileTypeBankAccountPosting->type_id;
        $bankAccountPosting->posting_date = Carbon::createFromFormat("YmdHis", substr($transactions->DTPOSTED, 0, 14))->format('d/m/Y H:i');
        $bankAccountPosting->bank_account_id = $bankAccount->id;
        $bankAccountPosting->document = $transactions->FITID;
        $bankAccountPosting->amount = ((float)$transactions->TRNAMT < 0) ? -((float)$transactions->TRNAMT) : (float)$transactions->TRNAMT;
        $bankAccountPosting->type = (string)$transactions->TRNTYPE === Ofx::ofxCredit ? TypeBankAccountPostingEnum::CREDIT : TypeBankAccountPostingEnum::DEBIT;
        $bankAccountPosting->expense_id = $keyFileTypeBankAccountPosting->expense_id;
        $bankAccountPosting->income_id = $keyFileTypeBankAccountPosting->income_id;
        return $this->bankAccountPostingService->calcAccountBalance($bankAccountPosting);
    }
    public function buildKeyFileTypeBankAccountPosting($transactions): KeyFileTypeBankAccountPosting
    {
        $keyFileTypeBankAccountPosting = $this->typeBankAccountPostingRepo->getTypeByKeyFile((string)$transactions->MEMO);
        if (is_null($keyFileTypeBankAccountPosting)) {
            $keyFileTypeBankAccountPosting = new KeyFileTypeBankAccountPosting();
            $keyFileTypeBankAccountPosting->type_id = 0;
            $keyFileTypeBankAccountPosting->expense_id = 0;
            $keyFileTypeBankAccountPosting->income_id = 0;
        }
        return $keyFileTypeBankAccountPosting;
    }
}
