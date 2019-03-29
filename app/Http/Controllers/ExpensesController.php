<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use App\Utilitarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('expense.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('expense.create');
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
            $expenses = new Expenses();
            $data = $request->all();
            $expenses->name = $data['name'];
            $expenses->amount = Utilitarios::formatReal($data['amount']);
            $expenses->isFixed = isset($data['isFixed']);
            $expenses->save();

            DB::commit();
            \Session::flash('message', ['msg' => 'Despesa Salva com sucesso', 'type' => 'success']);
            return redirect()->route('expense.index');
        }catch (\Exception $e){
            \Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->route('expense.index');
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
        $expense = Expenses::find($id);

        return view('expense.edit', compact('expense'));
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
        try{
            DB::beginTransaction();
            $data = $request->all();
            $expense = Expenses::find($request['id']);
            $expense->name = $data['name'];
            $expense->amount = Utilitarios::formatReal($data['amount']);
            $expense->isFixed = isset($data['isFixed']);
            $expense->save();

            DB::commit();
            \Session::flash('message', ['msg' => 'Despesas Atualizada com sucesso', 'type' => 'success']);
            return redirect()->route('expense.index');
        }catch (\Exception $e){
            \Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->route('expense.index');
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
        try{
            DB::beginTransaction();
            Expenses::find($id)->delete();
            DB::commit();
            \Session::flash('message', ['msg' => 'Despesa Excluida com sucesso', 'type' => 'success']);
            return redirect()->route('expense.index');
        }catch (\Exception $e){
            \Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->route('expense.index');
        }
    }


    public function get(Request $request){
        try{
            $model = Expenses::select(['id', 'name', 'amount', 'isFixed']);

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
                        ['type'=>'edit', 'url' => route('expense.edit',['id' => $model->id])],
                        ['type'=>'delete', 'url' => route('expense.destroy',['id' => $model->id]), 'id' => $model->id]
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
