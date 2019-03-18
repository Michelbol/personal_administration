<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bank_accounts = BankAccount::all();
        return view('bank_account.index', compact('bank_accounts'));
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

            DB::commit();
            return redirect()->route('bank_accounts.index');
        }catch (\Exception $e){
            Session::flash('message', $e->getMessage());
            return redirect()->route('bank_accounts.index');
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
        $bank_account = BankAccount::find($id);

        return view('bank_account.edit',compact('bank_account'));
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
        $bankaccount = BankAccount::find($id);

        $bankaccount->update($request->all());

        return redirect()->route('bank_accounts.index');
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
}
