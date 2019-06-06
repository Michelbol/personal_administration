<?php

namespace App\Http\Controllers;

use App\Models\BudgetFinancial;
use App\Models\BudgetFinancialPosting;
use App\Models\Expenses;
use App\Models\Income;
use App\Models\UserTenant;
use App\Utilitarios;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetFinancialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($tenant, Request $request)
    {
        try{
            $data = $request->all();
            $selected_user = isset($data['user_id']) ? UserTenant::find($data['user_id']) : Auth::user();
            $selected_user_id = (isset($selected_user) ? $selected_user->id : Auth::user()->id);
            $budgedFinancialYear = isset($request->year) ? $request->year : Carbon::now()->year;
            if(Income::where('id', '>', 0)->count() > 0 || Expenses::where('id', '>', 0)->count() > 0){
                $budgedFinancials = BudgetFinancial::where('year', $budgedFinancialYear)
                    ->where('user_id', $selected_user_id)
                    ->orderBy('month', 'asc')->get();
                $budgedFinancialsOpen = BudgetFinancial::where('year', $budgedFinancialYear)
                    ->where('user_id', $selected_user_id)
                    ->orderBy('month', 'asc')
                    ->where('isFinalized', false)->get();
                foreach ($budgedFinancialsOpen as $budget){
                    if(Carbon::now()->month> $budget->month){
                        $budget->isFinalized = true;
                        $budget->save();
                    }
                }
                while($budgedFinancials->count() === 0){
                    $this->createBudgetCurrentYear($tenant, $selected_user_id);
                    $budgedFinancials = BudgetFinancial::where('year', $budgedFinancialYear)
                        ->where('user_id', $selected_user_id)
                        ->orderBy('month', 'asc')
                        ->get();
                }
            }else{
                $index_expenses = routeTenant('expense.index');
                $index_incomes = routeTenant('income.index');
                \Session::flash('message', [
                    'msg' => "Para planejar seu orçamento, crie suas <a href='$index_expenses'>Despesas</a>/<a href='$index_incomes'>Receitas</a>",
                    'type' => 'danger']);
            }
            $users = UserTenant::all();
        }catch(\Exception $e){
            \Session::flash('message', ['msg' => "Erro ao acessa a página".$e->getMessage(), 'type' => 'danger']);
            return redirect()->back();
        }

        return view('budget_financial.index', compact('budgedFinancialYear', 'budgedFinancials', 'users', 'selected_user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($tenant, $year, $selected_user_id)
    {
        try{
            DB::beginTransaction();
            for($month = 1; $month <= 12; $month++){
                $budgetFinancial = new BudgetFinancial();
                $budgetFinancial->year = $year;
                $budgetFinancial->month = $month;
                $endActualMonth = Carbon::create($year, $month)->daysInMonth;
                if(Carbon::now()->isAfter(Carbon::create($year,$month, $endActualMonth))){
                    $budgetFinancial->isFinalized = true;
                }
                $budgetFinancial->user_id = (isset($selected_user_id) ? $selected_user_id : Auth::user()->id);
                $budgetFinancial->initial_balance = 0;

                $budgetFinancial->save();

                $this->createIncomesFixed($budgetFinancial, $year, $month);

                $this->createExpensesFixed($budgetFinancial, $year, $month);

            }
            DB::commit();
//            \Session::flash('message', ['msg' => 'Criado meses do ano '.$year, 'type' => 'success']);
        }catch (\Exception $e){
            \Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($tenant, $id)
    {
        $budgetFinancial = BudgetFinancial::find($id);
        $incomes = Income::all();
        $expenses = Expenses::all();

        return view('budget_financial.edit', compact('budgetFinancial', 'incomes', 'expenses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateInitialBalance(Request $request, $tenant, $id){
        try{
            DB::beginTransaction();
            $data = $request->all();
            $budgetFinancial = BudgetFinancial::find($id);
            $budgetFinancial->update(['initial_balance' => Utilitarios::formatReal($data['initial_balance'])]);
            BudgetFinancialPosting::recalcBalance($budgetFinancial);
            DB::commit();
            \Session::flash('message', ['msg' => 'Atualizado Saldo com sucesso', 'type' => 'success']);
            return redirect()->routeTenant('budget_financial.edit', [$budgetFinancial->id]);
        }catch (\Exception $e){
            dd($e->getMessage());
            \Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
        }
    }

    public function createBudgetCurrentYear($tenant, $selected_user_id){
        try{
            $this->store($tenant, Carbon::now()->year, $selected_user_id);
        }catch(\Exception $e){

        }
    }

    public function createIncomesFixed(BudgetFinancial $budgetFinancial, $year, $month){
        $incomes = Income::where('isFixed', true)->orderBy('due_date')->get();
        foreach($incomes as $index => $income){
            $budgetFinancialPosting = new BudgetFinancialPosting();
            $due_date = ($income->due_date > 0) ? $income->due_date : null;
            $budgetFinancialPosting->posting_date = Carbon::create($year, $month, $due_date);
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
