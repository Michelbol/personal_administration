<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use App\Utilitarios;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Yajra\DataTables\DataTables;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('expense.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('expense.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function store(Request $request)
    {
        $expense = new Expenses();
        $data = $request->all();
        $expense->name = $data['name'];
        $expense->amount = Utilitarios::formatReal($data['amount']);
        $expense->isFixed = isset($data['isFixed']);
        $expense->due_date = $data['due_date'];
        $expense->save();

        Session::flash('message', ['msg' => 'Despesa Salva com sucesso', 'type' => 'success']);
        return redirect()->routeTenant('expense.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @param $tenant
     * @return Response
     */
    public function edit($tenant, $id)
    {
        $expense = Expenses::find($id);

        return view('expense.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $expense = Expenses::find($request['id']);
        $expense->name = $data['name'];
        $expense->amount = Utilitarios::formatReal($data['amount']);
        $expense->isFixed = isset($data['isFixed']);
        $expense->due_date = $data['due_date'];
        $expense->save();

        Session::flash('message', ['msg' => 'Despesas Atualizada com sucesso', 'type' => 'success']);
        return redirect()->routeTenant('expense.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function destroy($tenant, $id)
    {
        Expenses::find($id)->delete();

        Session::flash('message', ['msg' => 'Despesa Excluida com sucesso', 'type' => 'success']);
        return redirect()->routeTenant('expense.index');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function get(Request $request){
        $model = Expenses::select(['id', 'name', 'amount', 'isFixed', 'due_date'])->orderByDesc('isFixed');

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
            ->addColumn('isFixed', function($model){
                return $model->isFixed === 1 ? '<i class="fas fa-thumbs-up"></i>' : '<i class="far fa-thumbs-down"></i>';
            })
            ->addColumn('amount', function($model){
                return 'R$: '.Utilitarios::getFormatReal($model->amount);
            })
            ->addColumn('actions', function ($model){
                return Utilitarios::getBtnAction([
                    ['type'=>'edit', 'url' => routeTenant('expense.edit',['expense' => $model->id])],
                    ['type'=>'delete', 'url' => routeTenant('expense.destroy',['expense' => $model->id]), 'id' => $model->id]
                ]);
            })
            ->rawColumns(['actions', 'isFixed'])
            ->toJson();
        return $response->original;
    }
}
