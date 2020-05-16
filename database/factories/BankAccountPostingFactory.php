<?php

/** @var Factory $factory */

use App\Models\BankAccountPosting;
use App\Models\Enum\TypeBankAccountPostingEnum;
use App\Models\Expenses;
use App\Models\Income;
use App\Models\TypeBankAccountPosting;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(BankAccountPosting::class, function (Faker $faker) {
    return [
        'document' => $faker->name,
        'posting_date' => $faker->dateTimeBetween('-1 year', 'now')->format('d/m/Y H:i'),
        'amount' => $faker->randomFloat(2, 0, 100000),
        'type' => $faker->randomElement(TypeBankAccountPostingEnum::getConstants()),
        'type_bank_account_posting_id' => TypeBankAccountPosting::inRandomOrder()->first(),
        'account_balance' => $faker->randomFloat(2, 0, 100000),
        'bank_account_id' => \App\Models\BankAccount::inRandomOrder()->first(),
        'income_id' => Income::inRandomOrder()->first(),
        'expense_id' => Expenses::inRandomOrder()->first(),
    ];
});
