<?php

namespace Database\Factories;

use App\Models\BankAccount;
use App\Models\Enum\TypeBankAccountPostingEnum;
use App\Models\Expenses;
use App\Models\Income;
use App\Models\TypeBankAccountPosting;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankAccountPostingFactory extends Factory {

    public function definition()
    {
        return [
            'document' => fake()->name,
            'posting_date' => fake()->dateTimeBetween('-1 year', 'now')->format('d/m/Y H:i'),
            'amount' => fake()->randomFloat(2, 0, 100000),
            'type' => fake()->randomElement(TypeBankAccountPostingEnum::getConstants()),
            'type_bank_account_posting_id' => TypeBankAccountPosting::inRandomOrder()->first(),
            'account_balance' => fake()->randomFloat(2, 0, 100000),
            'bank_account_id' => BankAccount::inRandomOrder()->first(),
            'income_id' => Income::inRandomOrder()->first(),
            'expense_id' => Expenses::inRandomOrder()->first(),
        ];
    }
}
