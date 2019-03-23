<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankAccountPosting;
use App\Models\TypeBankAccountPosting;
use App\Utilitarios;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\Curl\Util;
use Yajra\DataTables\DataTables;

class BankAccountPostingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $filter_type_bank_account_postings = TypeBankAccountPosting::all();
        $bankAccount            = BankAccount::find($id);
        return view('bank_account_posting.index', compact('bankAccount', 'filter_type_bank_account_postings'));
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
        try{
            DB::beginTransaction();
            $bankAccountPosting = new BankAccountPosting();
            $data = $request->all();
            $bankAccountPosting->document = $data['document'];
            $bankAccountPosting->posting_date = Utilitarios::formatDataCarbon($data['posting_date']);
            $bankAccountPosting->amount = $data['amount'];
            $bankAccountPosting->type = $data['type'];
            $bankAccountPosting->type_bank_account_posting_id = $data['type_bank_account_posting_id'];
            $bankAccountPosting->bank_account_id = $data['bank_account_id'];
            $balance = BankAccountPosting::where('bank_account_id', $bankAccountPosting->bank_account_id)
                ->where('posting_date', '<=', $bankAccountPosting->posting_date)
                ->orderBy('posting_date', 'desc')
                ->orderBy('id', 'desc')->first();
            if($balance === null){
                $bankAccountPosting->account_balance = $bankAccountPosting->amount;
            }else{
                $bankAccountPosting->account_balance = $balance->account_balance +
                    ($bankAccountPosting->type === 'C' ? $bankAccountPosting->amount : (-$bankAccountPosting->amount));

            }
            $bankAccountPosting->save();
            $this->recalcSaldo($bankAccountPosting->posting_date, $bankAccountPosting->bank_account_id);
            DB::commit();
            \Session::flash('message', ['msg' => 'Lançamento Salvo com sucesso', 'type' => 'success']);
            return redirect()->route('bank_account_posting.index', $bankAccountPosting->bank_account_id);
        }catch (\Exception $e){
            \Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->back();
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

    public function recalcSaldo(Carbon $date, $bank_account_id){
        try{
            DB::beginTransaction();
            $itens_recalc = BankAccountPosting::where('bank_account_id', $bank_account_id)
                ->where('posting_date', '>=', $date)
                ->orderBy('posting_date', 'asc')
                ->orderBy('id', 'asc')->get();
            $balance = $itens_recalc->first()->account_balance;
            $itens_recalc->shift();
            foreach($itens_recalc as $item){
                $balance = $balance +
                    ($item->type === 'C' ? $item->amount : (-$item->amount));
                $item->account_balance = $balance;
                $item->save();
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            \Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->route('bank_account_posting.file');
        }
    }

    public function readFileStore(Request $request){
        try{
            DB::beginTransaction();
            $files = $request->arquivo;
            foreach($files as $file){
                $this->readFile(file($file));
            }
            DB::commit();
            \Session::flash('message', ['msg' => 'Arquivo(s) Lido(s) Com Sucesso', 'type' => 'success']);
            return redirect()->route('bank_account_posting.file');
        }catch(\Exception $e){
            DB::rollBack();
            \Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->route('bank_account_posting.file');
        }
    }

    function readFile($file){
        $header = explode(';', $file[0]);
        $type_bank_account_posting_not_saves = [];

        $this->validationFile($header);

        foreach($file as $index => $posting){
            if($index === 0){
                continue;
            }
            $data = $this->clearStringFile($posting);
            $typeBankAccountPosting = new TypeBankAccountPosting();
            if($typeBankAccountPosting->getTypeBankAccountPosting($data[3]) === 0){
                array_push($type_bank_account_posting_not_saves, $data[3]);
                continue;
            }
            $bankAccount = $this->mountBankAccount($data);
            $bankAccountPosting = $this->mountBankAccountPosting($data, $bankAccount, $typeBankAccountPosting);
            if(sizeof($type_bank_account_posting_not_saves) === 0){
                $bankAccountPosting->save();
            }
        }
        if(sizeof($type_bank_account_posting_not_saves) !== 0){
            throw new \Exception('\nExistem tipos não salvos: '.implode(",", $type_bank_account_posting_not_saves));
        }
    }

    function validationFile($header){
        if( $header[0] === '"Conta"' &&
            $header[1] === '"Data_Mov"' &&
            $header[2] === '"Nr_Doc"' &&
            $header[3] === '"Historico"'&&
            $header[4] === '"Valor"' &&
            str_replace("\n", '', $header[5]) === '"Deb_Cred"'){
        }else{
            throw new \Exception('Arquivo inválido');
        }
    }
    function mountBankAccount($data){
        $agency         = substr($data[0],0,4);
        $operation      = substr($data[0],4,3);
        $number_account = substr($data[0],7,8);
        $digit_account  = substr($data[0],15,1);
        $bankAccount    = BankAccount::where([
            'agency'            => intval($agency),
            'operation'         => intval($operation),
            'number_account'    => intval($number_account),
            'digit_account'     => intval($digit_account)])->first();
        return $bankAccount;
    }

    function mountBankAccountPosting($data, BankAccount $bankAccount,TypeBankAccountPosting $typeBankAccountPosting){
        $bankAccountPosting = new BankAccountPosting();
        $bankAccountPosting->type_bank_account_posting_id = $typeBankAccountPosting->getTypeBankAccountPosting($data[3]);
        $bankAccountPosting->document = $data[0];
        $bankAccountPosting->posting_date = Carbon::create(substr($data[1], 0,4), substr($data[1], 4,2), substr($data[1], 6,2));
        $bankAccountPosting->bank_account_id = $bankAccount->id;
        $bankAccountPosting->document = $data[2];
        $bankAccountPosting->amount = $data[4];
        $bankAccountPosting->type = str_replace("\n", '', $data[5]);
        $balance = BankAccountPosting::where('bank_account_id',$bankAccountPosting->bank_account_id)
                                        ->where('posting_date', '<=', $bankAccountPosting->posting_date)
                                        ->orderBy('posting_date', 'desc')
                                        ->orderBy('id', 'desc')->first();
        if($balance === null){
            $bankAccountPosting->account_balance = $bankAccountPosting->amount;
        }else{
            $bankAccountPosting->account_balance = $balance->account_balance +
                ($bankAccountPosting->type === 'C' ? $bankAccountPosting->amount : (-$bankAccountPosting->amount));

        }
        return $bankAccountPosting;
    }

    function clearStringFile($posting){
        $data = explode(';', $posting);
        $data[0] = str_replace('"', '', $data[0]);
        $data[1] = str_replace('"', '', $data[1]);
        $data[2] = str_replace('"', '', $data[2]);
        $data[3] = str_replace('"', '', $data[3]);
        $data[4] = str_replace('"', '', $data[4]);
        $data[5] = str_replace('"', '', $data[5]);
        return $data;
    }

    public function file(){
        return view('bank_account_posting.file');
    }

    public function get(Request $request){
        try{
            $model = BankAccountPosting::where('bank_account_id', $request->id)
                ->join('type_bank_account_postings', 'type_bank_account_postings.id', 'bank_account_postings.type_bank_account_posting_id')
                ->select(['bank_account_postings.id', 'document','posting_date', 'amount', 'type',
                    'type_bank_account_postings.name as type_name', 'account_balance'])->orderBy('posting_date', 'desc')
            ->orderBy('bank_account_postings.id', 'desc');

            $response = DataTables::of($model)
                ->filter(function (Builder $query) use ($request){

                    if($request->type_name > 0){
                        $query->where('type_bank_account_postings.id', $request->type_name);
                    }
                    if($request->type !== "0"){
                        $query->where('type', $request->type);
                    }
                    if($request->posting_date !== null){
                        $explode = explode('-', $request->posting_date);
                        $dt_initial = Utilitarios::formatDataCarbon(Utilitarios::formatGetData(trim($explode[0])));
                        $dt_final = Utilitarios::formatDataCarbon(Utilitarios::formatGetData(trim($explode[1])));
                        $query->whereBetween('posting_date', [$dt_initial, $dt_final]);
                    }
                })
                ->addColumn('posting_date', function($model){
                    return $model->posting_date = Utilitarios::formatDataCarbon($model->posting_date)->format('d/m/Y H:i');
                })
                ->addColumn('amount', function($model){
                    return $model->amount = 'R$: '.Utilitarios::getFormatReal($model->amount);
                })
                ->addColumn('type', function($model){
                    return $model->type = $model->type === 'C' ? 'Crédito' : 'Débito';
                })->addColumn('account_balance', function($model){
                    return $model->amount = 'R$: '.Utilitarios::getFormatReal($model->account_balance);
                })
                ->toJson();
            return $response->original;
        }catch (\Exception $e){
            dd('erro!'.$e->getMessage());
        }
    }
}
