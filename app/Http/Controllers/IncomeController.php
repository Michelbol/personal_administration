<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Utilitarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('income.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('income.create');
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
            $income = new Income();
            $data = $request->all();
            $income->name = $data['name'];
            $income->amount = Utilitarios::formatReal($data['amount']);
            $income->isFixed = isset($data['isFixed']);
            $income->due_date = $data['due_date'];
            $income->save();

            DB::commit();
            \Session::flash('message', ['msg' => 'Receita Salva com sucesso', 'type' => 'success']);
            return redirect()->routeTenant('income.index');
        }catch (\Exception $e){
            \Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->routeTenant('income.index');
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
        $income = Income::find($id);

        return view('income.edit', compact('income'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $tenant, $id)
    {
        try{
            DB::beginTransaction();
            $data = $request->all();
            $income = Income::find($request['id']);
            $income->name = $data['name'];
            $income->amount = Utilitarios::formatReal($data['amount']);
            $income->isFixed = isset($data['isFixed']);
            $income->due_date = $data['due_date'];
            $income->save();

            DB::commit();
            \Session::flash('message', ['msg' => 'Receita Atualizada com sucesso', 'type' => 'success']);
            return redirect()->routeTenant('income.index');
        }catch (\Exception $e){
            \Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->routeTenant('income.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($tenant, $id)
    {
        try{
            DB::beginTransaction();
            Income::find($id)->delete();
            DB::commit();
            \Session::flash('message', ['msg' => 'Receita Excluida com sucesso', 'type' => 'success']);
            return redirect()->routeTenant('income.index');
        }catch (\Exception $e){
            \Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->routeTenant('income.index');
        }
    }


    public function get(Request $request){
        try{
            $model = Income::select(['id', 'name', 'amount', 'isFixed', 'due_date']);

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
                        ['type'=>'edit', 'url' => routeTenant('income.edit',['id' => $model->id])],
                        ['type'=>'delete', 'url' => routeTenant('income.destroy',['id' => $model->id]), 'id' => $model->id]
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
