<?php

namespace App\Http\Controllers;

use \Exception;
use Carbon\Carbon;
use Illuminate\View\View;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Services\BankAccountService;
use Illuminate\Contracts\View\Factory;

class BankAccountController extends CrudController
{
    protected $bankAccount;
    protected $msgStore = 'Banco Salvo com sucesso';
    protected $msgUpdate = 'Banco Atualizado com sucesso';
    protected $msgDestroy = 'Banco Deletado com sucesso';

    public function __construct(BankAccount $bankAccount, BankAccountService $service)
    {
        parent::__construct($service, $bankAccount);
        $this->bankAccount = $bankAccount;
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
        $bank_account = $this->bankAccount::find($id);
        $last_balance = $this->service->lastBalance($id);
        $year_search = isset($request->year) ? $request->year : Carbon::now()->year;
        $monthInterest = $this->service->calcMonthlyInterest($year_search, $id);
        $monthBalance = $this->service->calcMonthlyBalance($year_search, $id);
        return view('bank_account.edit',compact('bank_account','id','last_balance', 'monthInterest', 'year_search', 'monthBalance'));
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function get(Request $request){
        $model = $this->bankAccount::join('banks', 'bank_accounts.bank_id', 'banks.id')
            ->select(['bank_accounts.id', 'bank_accounts.name','banks.name as name_bank', 'bank_accounts.agency']);

            $response = DataTables::of($model)
                ->blacklist(['actions'])
                ->addColumn('actions', function ($model){
                    return getBtnAction([
                        ['type'=>'edit', 'url' => routeTenant('bank_accounts.edit',['bank_account' => $model->id])],
                        ['type'=>'other-a', 'url' => routeTenant('bank_account_posting.index',['id' => $model->id])],
                        ['type'=>'delete', 'url' => routeTenant('bank_accounts.destroy',['bank_account' => $model->id]), 'id' => $model->id]
                    ]);
                })
                ->rawColumns([ 'actions'])
                ->toJson();

            return $response->original;
    }
}
