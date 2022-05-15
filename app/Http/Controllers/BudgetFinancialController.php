<?php

namespace App\Http\Controllers;

use App\Models\BankAccountPosting;
use App\Models\BudgetFinancial;
use App\Models\BudgetFinancialPosting;
use App\Models\Enum\SessionEnum;
use App\Models\Enum\VisualizationBudgetFinancial;
use App\Models\Expenses;
use App\Models\Income;
use App\Models\UserTenant;
use App\Services\BudgetFinancialService;
use Auth;
use DB;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Session;

class BudgetFinancialController extends CrudController
{
    /**
     * @var BankAccountPosting
     */
    protected $bankAccountPosting;

    /**
     * @var BudgetFinancialPosting
     */
    protected $budgetFinancialPosting;

    /**
     * @var BudgetFinancialService
     */
    protected $service;

    public function __construct(
        BankAccountPosting $bankAccountPosting,
        BudgetFinancialPosting $budgetFinancialPosting,
        BudgetFinancialService $service)
    {
        $this->bankAccountPosting = $bankAccountPosting;
        $this->budgetFinancialPosting = $budgetFinancialPosting;
        parent::__construct($service, $bankAccountPosting);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $tenant
     * @param Request $request
     * @return RedirectResponse|Factory|View
     */
    public function index($tenant, Request $request)
    {
        $data = $request->all();
        $visualizationView = $request->get('visualization_view', VisualizationBudgetFinancial::TABLE);
        $selected_user = Auth::user();
        $now = Carbon::now();
        if(isset($data['user_id'])){
            $selected_user = UserTenant::find($data['user_id']);
        }
        $budgedFinancialYear = $now->year;
        if(isset($data['year'])){
            $budgedFinancialYear = $data['year'];
        }
        if(!($this->service->hasIncome() || $this->service->hasExpensive())){
            $budgetsFinancial = [];
            $index_expenses = routeTenant('expense.index');
            $index_incomes = routeTenant('income.index');
            $this->errorMessage(
                "Para planejar seu orçamento, crie suas <a href='$index_expenses'>Despesas</a>/<a href='$index_incomes'>Receitas</a>"
            );
        }else{
            $budgetsFinancial = $this
                ->service
                ->getBudgetsFinancial(
                    $budgedFinancialYear,
                    $selected_user->id
                );
            $this->service->closeBudgetsInPast($budgedFinancialYear, $selected_user->id);
            while($budgetsFinancial->count() === 0){
                $this->service->createBudgetCurrentYear($budgedFinancialYear, $selected_user->id);
                $budgetsFinancial = $this
                    ->service
                    ->getBudgetsFinancial(
                        $budgedFinancialYear,
                        $selected_user->id
                    );
            }
        }

        $users = UserTenant::all();

        return view('budget_financial.index',
            compact(
                'budgedFinancialYear',
                'budgetsFinancial',
                'users',
                'selected_user',
                'visualizationView'
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $tenant
     * @param int $id
     * @param Request $request
     * @return Factory|View
     */
    public function edit($tenant, $id, Request $request)
    {
        $budgetFinancial = BudgetFinancial::find($id);
        $incomes = Income::all();
        $expenses = Expenses::all();

        return view('budget_financial.edit', compact('budgetFinancial', 'incomes', 'expenses'));
    }

    /**
     * @param Request $request
     * @param $tenant
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function updateInitialBalance(Request $request, $tenant, $id){
        DB::beginTransaction();
        $data = $request->all();
        $budgetFinancial = BudgetFinancial::find($id);
        $budgetFinancial->update(['initial_balance' => formatReal($data['initial_balance'])]);
        BudgetFinancialPosting::recalcBalance($budgetFinancial);
        DB::commit();
        $this->successMessage('Atualizado Saldo com sucesso');
        return redirect()->routeTenant('budget_financial.edit', [$budgetFinancial->id]);
    }

    /**
     * @param $tenant
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function lastMonth($tenant, $id){
        DB::beginTransaction();
        $budgetFinancial = BudgetFinancial::find($id);
        $month = $budgetFinancial->month-1;
        $year = $budgetFinancial->year;
        if($budgetFinancial->month === 1){
            $month = 12;
            $year = $budgetFinancial->year-1;
        }
        /**
         * @var BudgetFinancial $budgetFinancialLastMonth
         */
        $budgetFinancialLastMonth = BudgetFinancial
            ::where('month', $month)
            ->where('year', $year)
            ->first();
        $balance = 0;
        if(isset($budgetFinancialLastMonth)){
            $income = $budgetFinancialLastMonth->budgetFinancialPostingsIncomes()->sum('amount');
            $expense = $budgetFinancialLastMonth->budgetFinancialPostingsExpenses()->sum('amount');
            $balance = $income-$expense+$budgetFinancialLastMonth->initial_balance;
        }
        $budgetFinancial->update(['initial_balance' => $balance]);
        BudgetFinancialPosting::recalcBalance($budgetFinancial);
        DB::commit();
        Session::flash('message', ['msg' => 'Atualizado Saldo com sucesso', 'type' => 'success']);
        return redirect()->routeTenant('budget_financial.edit', [$budgetFinancial->id]);
    }

    /**
     * @param $tenant
     * @param $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function budgetFinancialMonthByBankAccount($tenant, $id)
    {
        /**
         * @var $budgetFinancialPosting BudgetFinancialPosting
         * @var $budgetFinancial BudgetFinancial
         */
        $budgetFinancial = BudgetFinancial::findOrFail($id, ['id', 'month', 'year']);
        $budgetFinancialPostings = $budgetFinancial->budgetFinancialPostings;
        foreach ($budgetFinancialPostings as $budgetFinancialPosting){
            $budgetFinancialPosting->delete();
        }
        $startMonth = Carbon::create($budgetFinancial->year, $budgetFinancial->month)->firstOfMonth();
        $endMonth = Carbon::create($budgetFinancial->year, $budgetFinancial->month)->lastOfMonth();
        $bankAccountPostings = $this->bankAccountPosting->whereBetween('posting_date', [$startMonth, $endMonth])->get();
        foreach ($bankAccountPostings as $bankAccountPosting){
            $budgetFinancialPosting = new $this->budgetFinancialPosting();
            $budgetFinancialPosting->budget_financial_id = $budgetFinancial->id;
            $budgetFinancialPosting->income_id = $bankAccountPosting->income_id;
            $budgetFinancialPosting->expense_id = $bankAccountPosting->expense_id;
            $budgetFinancialPosting->posting_date = $bankAccountPosting->posting_date;
            $budgetFinancialPosting->amount = $bankAccountPosting->amount;
            $budgetFinancialPosting->account_balance = 0;
            $budgetFinancialPosting->save();
        }
        $budgetFinancialPosting::recalcBalance($budgetFinancial);
        Session::flash('message', ['msg' => 'Orçamento reiniciado com sucesso!', 'type' => SessionEnum::success]);
        return redirect()->routeTenant('budget_financial.edit', ['budget_financial' => $budgetFinancial->id]);
    }

    public function generateFixed($tenant, $id)
    {
        /**
         * @var $budgetFinancialPosting BudgetFinancialPosting
         * @var $budgetFinancial BudgetFinancial
         */
        $budgetFinancial = BudgetFinancial::findOrFail($id, ['id', 'month', 'year']);
        $this->service->deleteBudgetPostings($budgetFinancial);
        $this->service->createBudget($budgetFinancial->year, $budgetFinancial->month, auth()->id(), $budgetFinancial);
        Session::flash('message', ['msg' => 'Orçamento reiniciado com sucesso!', 'type' => SessionEnum::success]);
        return redirect()->routeTenant('budget_financial.edit', ['budget_financial' => $budgetFinancial->id]);
    }
}
