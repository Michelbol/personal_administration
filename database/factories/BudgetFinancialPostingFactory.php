<?php

/** @var Factory $factory */

use App\Models\BudgetFinancial;
use App\Models\BudgetFinancialPosting;
use App\Models\Expenses;
use App\Models\Income;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(BudgetFinancialPosting::class, function (Faker $faker) {
    if($faker->boolean){
        $income_id = Income::inRandomOrder()->first();
        $expense_id = null;
    }else{
        $income_id = null;
        $expense_id = Expenses::inRandomOrder()->first();
    }
    return [
        'posting_date' => $faker->date,
        'amount' => $faker->randomFloat(),
        'account_balance' => $faker->randomFloat(),
        'income_id' => $income_id,
        'expense_id' => $expense_id,
        'budget_financial_id' => BudgetFinancial::inRandomOrder()->first(),
    ];
});
