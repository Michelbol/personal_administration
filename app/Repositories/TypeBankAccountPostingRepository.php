<?php

namespace App\Repositories;

use App\Models\KeyFileTypeBankAccountPosting;

class TypeBankAccountPostingRepository
{
    public function getTypeByKeyFile($keyFile): ?KeyFileTypeBankAccountPosting {
        return KeyFileTypeBankAccountPosting::whereKeyFile($keyFile)->first();
    }
}
