<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\BudgetFinancialPosting
 *
 * @property int $id
 * @property string $posting_date
 * @property float $amount
 * @property float $account_balance
 * @property int|null $income_id
 * @property int|null $expense_id
 * @property int $budget_financial_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|BudgetFinancialPosting newModelQuery()
 * @method static Builder|BudgetFinancialPosting newQuery()
 * @method static Builder|BudgetFinancialPosting query()
 * @method static Builder|BudgetFinancialPosting whereAccountBalance($value)
 * @method static Builder|BudgetFinancialPosting whereAmount($value)
 * @method static Builder|BudgetFinancialPosting whereBudgetFinancialId($value)
 * @method static Builder|BudgetFinancialPosting whereCreatedAt($value)
 * @method static Builder|BudgetFinancialPosting whereExpenseId($value)
 * @method static Builder|BudgetFinancialPosting whereId($value)
 * @method static Builder|BudgetFinancialPosting whereIncomeId($value)
 * @method static Builder|BudgetFinancialPosting wherePostingDate($value)
 * @method static Builder|BudgetFinancialPosting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
