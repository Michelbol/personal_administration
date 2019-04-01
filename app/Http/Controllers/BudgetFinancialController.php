<?php

namespace App\Http\Controllers;

use App\Models\BudgetFinancial;
use App\Models\BudgetFinancialPosting;
use App\Models\Expenses;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BudgetFinancialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $budgedFinancialYear = isset($request->year) ? $request->year : Carbon::now()->year;

        $budgedFinancials = BudgetFinancial::where('year', $budgedFinancialYear)
            ->orderBy('month', 'asc')
            ->get();
        while($budgedFinancials->count() === 0){
            $this->createBudgetCurrentYear();
            $budgedFinancials = BudgetFinancial::where('year', $budgedFinancialYear)
                ->orderBy('month', 'asc')
                ->get();
        }

        return view('budget_financial.index', compact('budgedFinancialYear', 'budgedFinancials'));
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
    public function store($year)
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
    public function edit($id)
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

    public function createBudgetCurrentYear(){
        $this->store(Carbon::now()->year);
    }

    public function createIncomesFixed(BudgetFinancial $budgetFinancial, $year, $month){
        $incomes = Income::where('isFixed', true)->orderBy('due_date')->get();
        foreach($incomes as $income){
            $budgetFinancialPosting = new BudgetFinancialPosting();
            $due_date = ($income->due_date > 0) ? $income->due_date : null;
            $budgetFinancialPosting->posting_date = Carbon::create($year, $month, $due_date);
            $budgetFinancialPosting->amount = $income->amount;
            $budgetFinancialPosting->income_id = $income->id;
            $budgetFinancialPosting->expense_id = null;
            $budgetFinancialPosting->account_balance = 0;
            $budgetFinancialPosting->budget_financial_id = $budgetFinancial->id;
            $budgetFinancialPosting->save();
        }
    }

    public function createExpensesFixed(BudgetFinancial $budgetFinancial, $year, $month){
        $expenses = Expenses::where('isFixed', true)->orderBy('due_date')->get();
        foreach($expenses as $expense){
            $budgetFinancialPosting = new BudgetFinancialPosting();
            $due_date = ($expense->due_date > 0) ? $expense->due_date : null;
            $budgetFinancialPosting->posting_date = Carbon::create($year, $month, $due_date);
            $budgetFinancialPosting->amount = $expense->amount;
            $budgetFinancialPosting->income_id = null;
            $budgetFinancialPosting->expense_id = $expense->id;
            $budgetFinancialPosting->account_balance = 0;
            $budgetFinancialPosting->budget_financial_id = $budgetFinancial->id;
            $budgetFinancialPosting->save();
        }
    }
}
