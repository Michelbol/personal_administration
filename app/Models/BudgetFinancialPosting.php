<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetFinancialPosting extends Model
{
    protected $fillable = [
      'id',
      'posting_date',
      'amount',
      'account_balance',
      'income_id',
      'expense_id',
      'budget_financial_id',
    ];

    static function recalcBalance(BudgetFinancial $budgetFinancial){
        $budgetFinancialPostings = $budgetFinancial->budgetFinancialPostings()
            ->orderBy('posting_date', 'asc')
            ->orderBy('id', 'asc')->get();
        foreach($budgetFinancialPostings as $index => $budgetFinancialPosting){
            $balance = $index === 0 ? $budgetFinancial->initial_balance : $budgetFinancialPostings[$index-1]->account_balance;
            $budgetFinancialPosting->account_balance = isset($budgetFinancialPosting->income_id) ?
                $balance + $budgetFinancialPosting->amount :
                $balance - $budgetFinancialPosting->amount;
            $budgetFinancialPosting->save();
        }
    }
}
