<?php

namespace App\Repositories;

use App\Models\Bank;

class BankRepository
{

    public function findBankByNumber(int $bankId): Bank
    {
        return Bank
            ::where('number', 'like', "%$bankId%")
            ->first();
    }
}
