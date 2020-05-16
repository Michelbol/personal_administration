<?php


namespace App\Services;

use App\Models\BudgetFinancial;
use App\Models\BudgetFinancialPosting;
use App\Models\Car;
use App\Models\Expenses;
use App\Models\Income;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Session;

class BudgetFinancialService extends CRUDService
{
    /**
     * @var Expenses
     */
    protected $modelClass = Expenses::class;

    /**
     * @param BudgetFinancial $model
     * @param array $data
     */
    public function fill(&$model, $data)
    {
        $model->month = $data['month'];
        $model->year = $data['year'];
        $model->isFinalized = $data['isFinalized'];
        $model->initial_balance = $data['initial_balance'];
        $model->user_id = $data['user_id'];
    }

    /**
     * @return bool
     */
    public function hasExpensive()
    {
        return Expenses
                ::where('id', '>', 0)
                ->count() > 0;
    }

    /**
     * @return bool
     */
    public function hasIncome()
    {
        return Income
                ::where('id', '>', 0)
                ->count() > 0;
    }

    /**
     * @param int $year
     * @param $userId
     * @return BudgetFinancial[]|Builder[]|Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getBudgetsFinancial(int $year, $userId)
    {
        return $this
            ->queryBudgetsFinancial($year, $userId)
            ->get();
    }

    /**
     * @param int $year
     * @param $userId
     * @return BudgetFinancial|Builder|\Illuminate\Database\Query\Builder
     */
    public function queryBudgetsFinancial(int $year, $userId)
    {
        return BudgetFinancial
            ::whereUserId($userId)
            ->where('year', $year)
            ->orderBy('month');
    }

    /**
     * @param int $year
     * @param $userId
     * @return BudgetFinancial[]|Builder[]|Collection
     */
    public function getBudgetsFinancialOpen(int $year, $userId)
    {
        return $this
            ->queryBudgetsFinancial($year, $userId)
            ->where('isFinalized', false)
            ->get();
    }

    /**
     * @param int $year
     * @param $userId
     */
    public function closeBudgetsInPast(int $year, $userId)
    {
        $budgetsFinancialOpen = $this
            ->getBudgetsFinancialOpen(
                $year,
                $userId
            );
        foreach ($budgetsFinancialOpen as $budget){
            if(Carbon::now()->month > $budget->month){
                $this->closeBudget($budget);
            }
        }
    }

    /**
     * @param BudgetFinancial $budgetFinancial
     */
    public function closeBudget(BudgetFinancial $budgetFinancial)
    {
        $budgetFinancial->isFinalized = true;
        $budgetFinancial->save();
    }

    public function createBudgetCurrentYear(int $year, $userId)
    {
        DB::beginTransaction();
        for($month = 1; $month <= 12; $month++){
            $budgetFinancial = new BudgetFinancial();
            $budgetFinancial->year = $year;
            $budgetFinancial->month = $month;
            $endActualMonth = Carbon::create($year, $month)->daysInMonth;
            if(Carbon::now()->isAfter(Carbon::create($year,$month, $endActualMonth))){
                $budgetFinancial->isFinalized = true;
            }
            $budgetFinancial->user_id = (isset($selected_user_id) ? $selected_user_id : $userId);
            $budgetFinancial->initial_balance = 0;

            $budgetFinancial->save();

            $this->createIncomesFixed($budgetFinancial, $year, $month);

            $this->createExpensesFixed($budgetFinancial, $year, $month);

        }
        DB::commit();
    }


    public function createIncomesFixed(BudgetFinancial $budgetFinancial, $year, $month){
        $incomes = Income::where('isFixed', true)->orderBy('due_date')->get();
        foreach($incomes as $index => $income){
            $budgetFinancialPosting = new BudgetFinancialPosting();
            $due_date = ($income->due_date > 0) ? $income->due_date : null;
            $budgetFinancialPosting->posting_date = \Illuminate\Support\Carbon::create($year, $month, $due_date);
            $budgetFinancialPosting->amount = $income->amount;
            $budgetFinancialPosting->income_id = $income->id;
            $budgetFinancialPosting->expense_id = null;
            $balance = BudgetFinancialPosting::where('budget_financial_id', $budgetFinancial->id)
                ->where('posting_date', '<=', $budgetFinancialPosting->posting_date)
                ->orderBy('posting_date', 'desc')
                ->orderBy('id', 'desc')->first();
            $budgetFinancialPosting->account_balance = isset($balance) ?
                $balance->account_balance + $budgetFinancialPosting->amount :
                $budgetFinancial->initial_balance + $budgetFinancialPosting->amount;
            $budgetFinancialPosting->budget_financial_id = $budgetFinancial->id;
            $budgetFinancialPosting->save();
        }
    }

    public function createExpensesFixed(BudgetFinancial $budgetFinancial, $year, $month){
        $expenses = Expenses::where('isFixed', true)->orderBy('due_date')->get();
        foreach($expenses as $index => $expense){
            $budgetFinancialPosting = new BudgetFinancialPosting();
            $due_date = ($expense->due_date > 0) ? $expense->due_date : null;
            $budgetFinancialPosting->posting_date = Carbon::create($year, $month, $due_date);
            $budgetFinancialPosting->amount = $expense->amount;
            $budgetFinancialPosting->income_id = null;
            $budgetFinancialPosting->expense_id = $expense->id;
            $balance = BudgetFinancialPosting::where('budget_financial_id', $budgetFinancial->id)
                ->where('posting_date', '<=', $budgetFinancialPosting->posting_date)
                ->orderBy('posting_date', 'desc')
                ->orderBy('id', 'desc')->first();
            $budgetFinancialPosting->account_balance = isset($balance) ?
                $balance->account_balance - $budgetFinancialPosting->amount :
                $budgetFinancial->initial_balance - $budgetFinancialPosting->amount;
            $budgetFinancialPosting->budget_financial_id = $budgetFinancial->id;
            $budgetFinancialPosting->save();
        }
    }
}
