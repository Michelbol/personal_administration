<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Utilitarios;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('income.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('income.create');
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
        DB::beginTransaction();
        $income = new Income();
        $data = $request->all();
        $income->name = $data['name'];
        $income->amount = Utilitarios::formatReal($data['amount']);
        $income->isFixed = isset($data['isFixed']);
        $income->due_date = $data['due_date'];
        $income->save();

        DB::commit();
        $this->successMessage('Receita Salva com sucesso');
        return redirect()->routeTenant('income.index');
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
        $income = Income::find($id);

        return view('income.edit', compact('income'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $data = $request->all();
        $income = Income::find($request['id']);
        $income->name = $data['name'];
        $income->amount = Utilitarios::formatReal($data['amount']);
        $income->isFixed = isset($data['isFixed']);
        $income->due_date = $data['due_date'];
        $income->save();

        DB::commit();
        $this->successMessage('Receita Atualizada com sucesso');
        return redirect()->routeTenant('income.index');
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
        Income::find($id)->delete();
        $this->successMessage('Receita Excluida com sucesso');
        return redirect()->routeTenant('income.index');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function get(Request $request){
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
                    ['type'=>'edit', 'url' => routeTenant('income.edit',['income' => $model->id])],
                    ['type'=>'delete', 'url' => routeTenant('income.destroy',['income' => $model->id]), 'id' => $model->id]
                ]);
            })
            ->rawColumns(['actions', 'isFixed'])
            ->toJson();
        return $response->original;
    }
}
