<?php

namespace App\Http\Controllers;

use App\Services\BankAccountService;
use \Exception;
use Carbon\Carbon;
use Illuminate\View\View;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use App\Models\Enum\SessionEnum;
use Illuminate\Support\Facades\DB;
use App\Models\BankAccountPosting;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Session;

class BankAccountController extends CrudController
{
    protected $db;
    protected $carbon;
    protected $session;
    protected $bankAccount;
    protected $bankAccountPosting;
    protected $msgStore = 'Banco Salvo com sucesso';

    public function __construct(DB $db, BankAccount $bankAccount, BankAccountPosting $bankAccountPosting, Carbon $carbon, Session $session, BankAccountService $service)
    {
        parent::__construct($service, $bankAccount);
        $this->db = $db;
        $this->carbon = $carbon;
        $this->session = $session;
        $this->bankAccount = $bankAccount;
        $this->bankAccountPosting = $bankAccountPosting;
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
        $last_balance = $this->bankAccountPosting->where('bank_account_id', $id)
            ->orderBy('posting_date','desc')
            ->orderBy('id','desc')->first();
        $year_search = isset($request->year) ? $request->year : Carbon::now()->year;
        $last_balance = isset($last_balance) ? $last_balance->account_balance :'0.00';
        $monthInterest = $this->bankAccount::calcMonthlyInterest($year_search, $bank_account->id);
        $monthInterest = collect($monthInterest)->implode(',');
        $monthBalance = $this->bankAccount::calcMonthlyBalance($year_search, $bank_account->id);
        $monthBalance = collect($monthBalance)->implode(',');
        return view('bank_account.edit',compact('bank_account','last_balance', 'monthInterest', 'year_search', 'monthBalance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $tenant
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update($tenant, Request $request, $id)
    {
        try{
            $bankAccount = $this->bankAccount::find($id);

            $bankAccount->update($request->all());

            $this->session::flash('message', ['msg' => 'Banco Atualizado com sucesso', 'type' => SessionEnum::success]);
            return redirect()->routeTenant('bank_accounts.index');
        }catch (Exception $e){
            $this->session::flash('message', ['msg' => $e->getMessage(), 'type' => SessionEnum::error]);
            return redirect()->routeTenant('bank_accounts.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $tenant
     * @param int $id
     * @return Response
     */
    public function destroy($tenant, $id)
    {
        try{
            $bank_account = $this->bankAccount::find($id);
            $bank_account->delete();

            Session::flash('message', ['msg' => 'Banco Deletado com sucesso', 'type' => SessionEnum::success]);
            return redirect()->routeTenant('bank_accounts.index');
        }catch (Exception $e){
            Session::flash('message', ['msg' => $e->getMessage(), 'type' => SessionEnum::error]);
            return redirect()->routeTenant('bank_accounts.index');
        }
    }

    public function get(Request $request){
        $model = $this->bankAccount::join('banks', 'bank_accounts.bank_id', 'banks.id')
            ->select(['bank_accounts.id', 'bank_accounts.name','banks.name as name_bank', 'bank_accounts.agency']);

        try{
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
        }catch (Exception $e){
            Session::flash('message', ['msg' => $e->getMessage(), 'type' => SessionEnum::error]);
            return redirect()->back();
        }
    }
}
