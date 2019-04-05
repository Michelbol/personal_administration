<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankAccountPosting;
use App\Utilitarios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('bank_account.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bank_account.create');
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
            $bankAccount = new BankAccount();
            $data = $request->all();
            $bankAccount->name = $data['name'];
            $bankAccount->agency = $data['agency'];
            $bankAccount->digit_agency = $data['digit_agency'];
            $bankAccount->number_account = $data['number_account'];
            $bankAccount->digit_account = $data['digit_account'];
            $bankAccount->bank_id = $data['bank_id'];
            $bankAccount->save();

            $bankAccountPosting = new BankAccountPosting();
            $bankAccountPosting->document = 'Abertura Conta';
            $bankAccountPosting->posting_date = Carbon::now();
            $bankAccountPosting->amount = $data['account_balance'];
            $bankAccountPosting->type = 'C';
            $bankAccountPosting->type_bank_account_posting_id = 1;
            $bankAccountPosting->account_balance = $data['account_balance'];
            $bankAccountPosting->bank_account_id = $bankAccount->id;
            $bankAccountPosting->save();

            DB::commit();
            \Session::flash('message', ['msg' => 'Banco Salvo com sucesso', 'type' => 'success']);
            return redirect()->routeTenant('bank_accounts.index');
        }catch (\Exception $e){
            \Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->routeTenant('bank_accounts.index');
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
    public function edit($tenant, $id, Request $request)
    {
        $bank_account = BankAccount::find($id);
        $last_balance = BankAccountPosting::where('bank_account_id', $id)
            ->orderBy('posting_date','desc')
            ->orderBy('id','desc')->first();
        $year_search = isset($request->year) ? $request->year : Carbon::now()->year;
        $last_balance = isset($last_balance) ? $last_balance->account_balance :'0.00';
        $monthInterest = BankAccount::calcMonthlyInterest($year_search, $bank_account->id);
        $monthInterest = collect($monthInterest)->implode(',');
        $monthBalance = BankAccount::calcMonthlyBalance($year_search, $bank_account->id);
        $monthBalance = collect($monthBalance)->implode(',');
        return view('bank_account.edit',compact('bank_account','last_balance', 'monthInterest', 'year_search', 'monthBalance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($tenant, Request $request, $id)
    {
        try{
            $bankaccount = BankAccount::find($id);

            $bankaccount->update($request->all());

            \Session::flash('message', ['msg' => 'Banco Atualizado com sucesso', 'type' => 'success']);
            return redirect()->routeTenant('bank_accounts.index');
        }catch (\Exception $e){
            \Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->routeTenant('bank_accounts.index');
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
            $bank_account = BankAccount::find($id);
            $bank_account->delete();

            \Session::flash('message', ['msg' => 'Banco Deletado com sucesso', 'type' => 'success']);
            return redirect()->routeTenant('bank_accounts.index');
        }catch (\Exception $e){
            \Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->routeTenant('bank_accounts.index');
        }
    }

    public function get(Request $request){
        $model = BankAccount::join('banks', 'bank_accounts.bank_id', 'banks.id')
            ->select(['bank_accounts.id', 'bank_accounts.name','banks.name as name_bank', 'bank_accounts.agency']);

        $response = DataTables::of($model)
            ->blacklist(['actions'])

            ->addColumn('actions', function ($model){
                return Utilitarios::getBtnAction([
                    ['type'=>'edit', 'url' => routeTenant('bank_accounts.edit',['id' => $model->id])],
                    ['type'=>'other-a', 'url' => routeTenant('bank_account_posting.index',['id' => $model->id])],
                    ['type'=>'delete', 'url' => routeTenant('bank_accounts.destroy',['id' => $model->id]), 'id' => $model->id]
                ]);
            })
            ->rawColumns([ 'actions'])
            ->toJson();

        return $response->original;
    }
}
