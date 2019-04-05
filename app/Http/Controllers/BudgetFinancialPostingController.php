<?php

namespace App\Http\Controllers;

use App\Models\BudgetFinancial;
use App\Models\BudgetFinancialPosting;
use App\Models\Expenses;
use App\Models\Income;
use App\Utilitarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BudgetFinancialPostingController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $budgetFinancialPosting = new BudgetFinancialPosting();
            $data = $request->all();
            $budgetFinancialPosting->posting_date = Utilitarios::formatDataCarbon($data['posting_date']);
            $budgetFinancialPosting->amount = Utilitarios::formatReal($data['amount']);
            $budgetFinancialPosting->budget_financial_id = $data['budget_financial_id'];
            if(isset($data['new_expense'])){
                $expense = Expenses::create([
                    'name' => $data['new_expense'],
                    'amount' => $data['amount']
                ]);
                $data['expense_id'] = $expense->id;
            }
            $budgetFinancialPosting->expense_id = $data['expense_id'];
            if(isset($data['new_income'])){
                $income = Income::create([
                    'name' => $data['new_income'],
                    'amount' => $data['amount']
                ]);
                $data['income_id'] = $income->id;
            }
            $budgetFinancialPosting->income_id = $data['income_id'];
            $budgetFinancialPosting->account_balance = 0;
            $budgetFinancialPosting->save();
            BudgetFinancialPosting::recalcBalance(BudgetFinancial::find($budgetFinancialPosting->budget_financial_id));
            DB::commit();
            \Session::flash('message', ['msg' => 'Lançamento Salvo com sucesso', 'type' => 'success']);
            return redirect()->routeTenant('budget_financial.edit', ['id' => $budgetFinancialPosting->budget_financial_id]);
        }catch (\Exception $e){
            \Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->back();
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$tenant, $id)
    {
        try{
            DB::beginTransaction();
            $budgetFinancialPosting = BudgetFinancialPosting::find($id);
            $data = $request->all();
            $budgetFinancialPosting->posting_date = Utilitarios::formatDataCarbon($data['posting_date']);
            $budgetFinancialPosting->amount = Utilitarios::formatReal(str_replace('R$: ', '', $data['amount']));
            if(isset($data['new_expense'])){
                $expense = Expenses::create([
                    'name' => $data['new_expense'],
                    'amount' => $data['amount']
                ]);
                $data['expense_id'] = $expense->id;
            }
            $budgetFinancialPosting->expense_id = $data['expense_id'];
            if(isset($data['new_income'])){
                $income = Income::create([
                    'name' => $data['new_income'],
                    'amount' => $data['amount']
                ]);
                $data['income_id'] = $income->id;
            }
            $budgetFinancialPosting->income_id = $data['income_id'];
            $budgetFinancialPosting->account_balance = 0;
            $budgetFinancialPosting->save();
            BudgetFinancialPosting::recalcBalance(BudgetFinancial::find($budgetFinancialPosting->budget_financial_id));
            DB::commit();
            \Session::flash('message', ['msg' => 'Lançamento Atualizado com sucesso', 'type' => 'success']);
            return redirect()->routeTenant('budget_financial.edit', ['id' => $budgetFinancialPosting->budget_financial_id]);
        }catch (\Exception $e){
            \Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->back();
        }
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

    public function get(Request $request){
        try{
            $model = BudgetFinancialPosting::where('budget_financial_id', $request->id)
                ->leftjoin('incomes', 'incomes.id', 'budget_financial_postings.income_id')
                ->leftjoin('expenses', 'expenses.id', 'budget_financial_postings.expense_id')
                ->select(['budget_financial_postings.amount', 'budget_financial_postings.id',
                'budget_financial_postings.account_balance','budget_financial_postings.posting_date',
                'incomes.name as incomes_name', 'incomes.id as income_id', 'expenses.id as expenses_id',
                'expenses.name as expenses_name', 'expenses.isFixed as expense_isFixed', 'incomes.isFixed as income_isFixed'])
                ->orderBy('budget_financial_postings.posting_date', 'asc');

            $response = DataTables::of($model)
//                ->filter(function (Builder $query) use ($request){

//                    if($request->type_name > 0){
//                        $query->where('type_bank_account_postings.id', $request->type_name);
//                    }
//                    if($request->type !== "0"){
//                        $query->where('type', $request->type);
//                    }
//                    if($request->posting_date !== null){
//                        $explode = explode('-', $request->posting_date);
//                        $dt_initial = Utilitarios::formatDataCarbon(trim($explode[0]));
//                        $dt_final = Utilitarios::formatDataCarbon(trim($explode[1]));
//                        $query->whereBetween('posting_date', [$dt_initial, $dt_final]);
//                    }
//                })
                ->addColumn('name', function($model){
                    return isset($model->expenses_name) ? $model->expenses_name : $model->incomes_name;
                })
                ->addColumn('type', function($model){
                    return isset($model->expenses_name) ? 'Despesa' : 'Receita';
                })
                ->addColumn('isFixed', function($model){
                    $fixed =  '<i class="fas fa-thumbs-up"></i>';
                    $not_fixed = '<i class="far fa-thumbs-down"></i>';
                    return isset($model->expenses_name) ? ($model->expense_isFixed === 1 ? $fixed : $not_fixed ) :
                        ($model->income_isFixed === 1 ? $fixed : $not_fixed);
                })
                ->addColumn('posting_date', function($model){
                    return $model->posting_date = Utilitarios::formatDataCarbon($model->posting_date)->format('d/m/Y');
                })
                ->addColumn('amount', function($model){
                    return isset($model->expenses_name) ? $model->amount = '-R$: '.Utilitarios::getFormatReal($model->amount) :
                        $model->amount = 'R$: '.Utilitarios::getFormatReal($model->amount) ;
                })
                ->addColumn('account_balance', function($model){
                    return 'R$: '.Utilitarios::getFormatReal($model->account_balance);
                })
                ->addColumn('actions', function($model){
                    return Utilitarios::getBtnAction([
                        ['type'=>'others', 'name' => 'open-modal-budget-financial-posting', 'class' => 'fa fa-edit', 'disabled' => true,
                        'tooltip' => 'Editar'],
                    ]);
                })
                ->rawColumns(['actions', 'isFixed'])
                ->toJson();
            return $response->original;
        }catch (\Exception $e){
            dd('erro!'.$e->getMessage());
        }
    }
}
