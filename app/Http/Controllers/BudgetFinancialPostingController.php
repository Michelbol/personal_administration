<?php

namespace App\Http\Controllers;

use App\Models\BudgetFinancial;
use App\Models\BudgetFinancialPosting;
use App\Models\Expenses;
use App\Models\Income;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Session;
use Yajra\DataTables\DataTables;

class BudgetFinancialPostingController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $budgetFinancialPosting = new BudgetFinancialPosting();
        $data = $request->all();

        $budgetFinancialPosting->posting_date = formatDataCarbon($data['posting_date']);
        $budgetFinancialPosting->amount = formatReal($data['amount']);
        $budgetFinancialPosting->budget_financial_id = $data['budget_financial_id'];
        if(isset($data['new_expense'])){
            $expense = Expenses::create([
                'name' => $data['new_expense'],
                'amount' => formatReal($data['amount'])
            ]);
            $data['expense_id'] = $expense->id;
        }
        $budgetFinancialPosting->expense_id = $data['expense_id'];
        if(isset($data['new_income'])){
            $income = Income::create([
                'name' => $data['new_income'],
                'amount' => formatReal($data['amount'])
            ]);
            $data['income_id'] = $income->id;
        }
        $budgetFinancialPosting->income_id = $data['income_id'];
        $budgetFinancialPosting->account_balance = 0;
        $budgetFinancialPosting->save();
        BudgetFinancialPosting::recalcBalance(BudgetFinancial::find($budgetFinancialPosting->budget_financial_id));
        DB::commit();
        $this->successMessage('Lançamento Salvo com sucesso');
        return redirect()->routeTenant('budget_financial.edit', ['budget_financial' => $budgetFinancialPosting->budget_financial_id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function update(Request $request,$tenant, $id)
    {
        DB::beginTransaction();
        $budgetFinancialPosting = BudgetFinancialPosting::find($id);
        $data = $request->all();
        $budgetFinancialPosting->posting_date = formatDataCarbon($data['posting_date']);
        $budgetFinancialPosting->amount = formatReal(str_replace('R$: ', '', $data['amount']));
        if(isset($data['new_expense'])){
            $expense = Expenses::create([
                'name' => $data['new_expense'],
                'amount' => $budgetFinancialPosting->amount
            ]);
            $data['expense_id'] = $expense->id;
        }
        $budgetFinancialPosting->expense_id = $data['expense_id'];
        if(isset($data['new_income'])){
            $income = Income::create([
                'name' => $data['new_income'],
                'amount' => $budgetFinancialPosting->amount
            ]);
            $data['income_id'] = $income->id;
        }
        $budgetFinancialPosting->income_id = $data['income_id'];
        $budgetFinancialPosting->account_balance = 0;
        $budgetFinancialPosting->save();
        BudgetFinancialPosting::recalcBalance(BudgetFinancial::find($budgetFinancialPosting->budget_financial_id));
        DB::commit();
        Session::flash('message', ['msg' => 'Lançamento Atualizado com sucesso', 'type' => 'success']);
        return redirect()->routeTenant('budget_financial.edit', ['budget_financial' => $budgetFinancialPosting->budget_financial_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $tenant
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function destroy($tenant, $id)
    {
        $budget_financial_posting = BudgetFinancialPosting::find($id);
        $budget_financial_posting->delete();
        BudgetFinancialPosting::recalcBalance(BudgetFinancial::find($budget_financial_posting->budget_financial_id));
        Session::flash('message', ['msg' => 'Lançamento deletado com sucesso', 'type' => 'success']);
        return redirect()->routeTenant('budget_financial.edit', ['budget_financial' => $budget_financial_posting->budget_financial_id]);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function get(Request $request){
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
//                        $dt_initial = formatDataCarbon(trim($explode[0]));
//                        $dt_final = formatDataCarbon(trim($explode[1]));
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
                return $model->posting_date = formatDataCarbon($model->posting_date)->format('d/m/Y');
            })
            ->addColumn('amount', function($model){
                return isset($model->expenses_name) ? $model->amount = '-R$: '.getFormatReal($model->amount) :
                    $model->amount = 'R$: '.getFormatReal($model->amount) ;
            })
            ->addColumn('account_balance', function($model){
                return 'R$: '.getFormatReal($model->account_balance);
            })
            ->addColumn('actions', function($model){
                return getBtnAction([
                    ['type'=>'others', 'name' => 'open-modal-budget-financial-posting', 'class' => 'fa fa-edit', 'disabled' => true,'tooltip' => 'Editar'],
                    ['url' => routeTenant('budget_financial_posting.destroy', ['budget_financial_posting' => $model->id]), 'id' => $model->id,'type'=>'delete', 'name' => '<i class="fa fa-times"></i>', 'class' => 'btn', 'disabled' => true,'tooltip' => 'Excluir'],
                ]);
            })
            ->rawColumns(['actions', 'isFixed'])
            ->toJson();
        return $response->original;
    }
}
