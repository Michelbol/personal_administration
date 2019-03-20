<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankAccountPosting;
use App\Models\TypeBankAccountPosting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankAccountPostingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $bankAccountPostings    = BankAccountPosting::where('bank_account_id', $id)->get();
        $bankAccount            = BankAccount::find($id);
        return view('bank_account_posting.index', compact('bankAccountPostings', 'bankAccount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
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

    public function readFileStore(Request $request){
        try{
            DB::beginTransaction();
            $file = file($request->arquivo);

            $header = explode(';', $file[0]);

            $arquivo_valido = false;

            $qtd_posting = sizeof($file)-1;
            $type_bank_account_posting_not_saves = [];

            if( $header[0] === '"Conta"' &&
                $header[1] === '"Data_Mov"' &&
                $header[2] === '"Nr_Doc"' &&
                $header[3] === '"Historico"'&&
                $header[4] === '"Valor"' &&
                str_replace("\n", '', $header[5]) === '"Deb_Cred"'){
                $arquivo_valido = true;
            }else{
                throw new \Exception('Arquivo inválido');
            }

            foreach($file as $index => $posting){
                if($index === 0){
                    continue;
                }
                $typeBankAccountPostig = new TypeBankAccountPosting();
                $bankAccountPosting = new BankAccountPosting();
                $data = explode(';', $posting);
                $data[0] = str_replace('"', '', $data[0]);
                $data[1] = str_replace('"', '', $data[1]);
                $data[2] = str_replace('"', '', $data[2]);
                $data[3] = str_replace('"', '', $data[3]);

                $bankAccountPosting->type_bank_account_postings_id = $typeBankAccountPostig->getTypeBankAccountPosting($data[3]);
                if($bankAccountPosting->type_bank_account_postings_id === 0){
                    array_push($type_bank_account_posting_not_saves, $data[3]);
                    continue;
                }

                $data[4] = str_replace('"', '', $data[4]);
                $data[5] = str_replace('"', '', $data[5]);
                $bankAccountPosting->document = $data[0];
                $agency         = substr($data[0],0,4);
                $operation      = substr($data[0],4,3);
                $number_account = substr($data[0],7,8);
                $digit_account  = substr($data[0],15,1);
                $bankAccount    = BankAccount::where([
                    'agency'            => intval($agency),
                    'operation'         => intval($operation),
                    'number_account'    => intval($number_account),
                    'digit_account'     => intval($digit_account)])->first();
                $dt_lancamento = Carbon::create(substr($data[1], 0,4), substr($data[1], 4,2), substr($data[1], 6,2));
                $bankAccountPosting->posting_date = $dt_lancamento;
                $bankAccountPosting->bank_account_id = $bankAccount->id;
                $bankAccountPosting->document = $data[2];
                $bankAccountPosting->amount = $data[4];
                $bankAccountPosting->type = str_replace("\n", '', $data[5]);

                if(sizeof($type_bank_account_posting_not_saves) === 0){
                    $bankAccountPosting->save();
                }
            }
            if(sizeof($type_bank_account_posting_not_saves) !== 0){
                throw new \Exception('\nExistem tipos não salvos: '.implode(",", $type_bank_account_posting_not_saves));
            }
            DB::commit();
            \Session::flash('message', ['msg' => 'Banco Deletado com sucesso', 'type' => 'success']);
            return redirect()->route('bank_account_posting.file');
        }catch(\Exception $e){
            DB::rollBack();
            \Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->route('bank_account_posting.file');
        }
    }

    public function file(){
        return view('bank_account_posting.file');
    }
}
