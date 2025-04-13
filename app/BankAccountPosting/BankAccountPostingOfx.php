<?php

namespace App\BankAccountPosting;

use App\Models\BankAccount;
use App\Ofx;

interface BankAccountPostingOfx
{
    public function saveBankAccountPostingFromOfx(Ofx $ofx, BankAccount $bankAccount): void;

    public function retrieveTypeBankAccountPostingNotSaved(): array;
}
