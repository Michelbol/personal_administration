<?php

namespace App\Http\Controllers;

use App\Models\CreditCard;
use App\Utilitarios;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Yajra\DataTables\DataTables;

class CredCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('cred_card.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('cred_card.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if(isset($data['limit'])){
            $data['limit'] = Utilitarios::formatReal($data['limit']);
        }
        $cred_card = new CreditCard();
        $cred_card->fill($data);
        $cred_card->save();

        $this->successMessage('Cartão de crédito salvo com sucesso');

        return redirect()->routeTenant('cred_card.index');
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
        $cred_card = CreditCard::find($id);

        return view('cred_card.edit', compact('cred_card'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @param $tenant
     * @return Response
     */
    public function update(Request $request, $tenant, $id)
    {
        $data = $request->all();
        $data['limit'] = Utilitarios::formatReal($data['limit']);
        $cred_card = CreditCard::find($id);
        $cred_card->fill($data);
        $cred_card->save();

        $this->successMessage('Cartão Atualizado com sucesso');
        return redirect()->routeTenant('cred_card.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param $tenant
     * @return Response
     * @throws Exception
     */
    public function destroy($tenant, $id)
    {
        $cred_card = CreditCard::find($id);
        $cred_card->delete();

        Session::flash('message', ['msg' => 'Cartão de Crédito Deletado com sucesso', 'type' => 'success']);
        return redirect()->routeTenant('cred_card.index');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function get(Request $request){
        $model = CreditCard::select(['id', 'name','limit', 'default_closing_date']);

        $response = DataTables::of($model)
            ->blacklist(['actions'])

            ->addColumn('limit', function ($model) {
                return 'R$: '.Utilitarios::getFormatReal($model->limit);
            })
            ->addColumn('actions', function ($model){
                return Utilitarios::getBtnAction([
                    ['type'=>'edit', 'url' => routeTenant('cred_card.edit',['id' => $model->id])],
                    ['type'=>'other-a', 'url' => routeTenant('cred_card.index',['id' => $model->id]), 'name' => 'Faturas'],
                    ['type'=>'delete', 'url' => routeTenant('cred_card.destroy',['id' => $model->id]), 'id' => $model->id]
                ]);
            })
            ->rawColumns(['actions'])
            ->toJson();

        return $response->original;
    }
}
