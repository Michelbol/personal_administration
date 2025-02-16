<?php

namespace Database\Factories;

use App\Models\BudgetFinancial;
use App\Models\Expenses;
use App\Models\Income;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetFinancialPostingFactory extends Factory
{

    public function definition()
    {
        if(fake()->boolean){
            $income_id = Income::inRandomOrder()->first();
            $expense_id = null;
        }else{
            $income_id = null;
            $expense_id = Expenses::inRandomOrder()->first();
        }
        return [
            'posting_date' => fake()->date,
            'amount' => fake()->randomFloat(),
            'account_balance' => fake()->randomFloat(),
            'income_id' => $income_id,
            'expense_id' => $expense_id,
            'budget_financial_id' => BudgetFinancial::inRandomOrder()->first(),
        ];
    }
}
