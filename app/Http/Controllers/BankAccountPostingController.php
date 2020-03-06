<?php

namespace App\Http\Controllers;

use \Session;
use App\Ofx;
use \Exception;
use Carbon\Carbon;
use App\Utilitarios;
use App\Models\Bank;
use App\Models\Income;
use App\Models\Expenses;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use App\Models\BankAccountPosting;
use Illuminate\Support\Facades\DB;
use App\Models\TypeBankAccountPosting;
use Illuminate\Database\Eloquent\Builder;

class BankAccountPostingController extends Controller
{

    protected $db;
    protected $income;
    protected $session;
    protected $expenses;
    protected $bankAccount;
    protected $utilitarios;
    protected $bankAccountPosting;
    protected $typeBankAccountPosting;

    public function __construct(TypeBankAccountPosting $typeBankAccountPosting, DB $db, BankAccount $bankAccount,
                                Income $income, Expenses $expenses, BankAccountPosting $bankAccountPosting,
                                Utilitarios $utilitarios, Session $session)
    {
        $this->db = $db;
        $this->income = $income;
        $this->session = $session;
        $this->expenses = $expenses;
        $this->bankAccount = $bankAccount;
        $this->utilitarios = $utilitarios;
        $this->bankAccountPosting = $bankAccountPosting;
        $this->typeBankAccountPosting = $typeBankAccountPosting;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $tenant
     * @param $id
     * @return Response
     */
    public function index($tenant, $id)
    {
        $filterTypeBankAccountPostings = $this->typeBankAccountPosting::all();
        $bankAccount            = $this->bankAccount::find($id);
        $incomes                = $this->income::all(['id', 'name']);
        $expenses               = $this->expenses::all(['id', 'name']);
        $variables = [
          'bankAccount' => $bankAccount,
          'filterTypeBankAccountPostings' => $filterTypeBankAccountPostings,
          'incomes' => $incomes,
          'expenses' => $expenses
        ];
        return view('bank_account_posting.index', $variables);
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
        try{
            $this->db::beginTransaction();
            $data = $request->all();
            $where_balance = [];
            if(isset($data['id']) && $data['id'] > 0){
                $bankAccountPosting = $this->bankAccountPosting::find($data['id']);
                array_push($where_balance, ['id', '!=', $data['id']]);
            }else{
                $bankAccountPosting = new $this->bankAccountPosting();
            }
            if(isset($data['new_income'])){
                $income = $this->income::create([
                    'name' => $data['new_income'],
                    'amount' => $this->utilitarios::formatReal($data['amount'])
                ]);
                $data['income_id'] = $income->id;
            }
            if(isset($data['new_expense'])){
                $expense = $this->expenses::create([
                    'name' => $data['new_expense'],
                    'amount' => $this->utilitarios::formatReal($data['amount'])
                ]);
                $data['expense_id'] = $expense->id;
            }
            $bankAccountPosting->document = $data['document'];
            $bankAccountPosting->posting_date = $this->utilitarios::formatDataCarbon($data['posting_date']);
            array_push($where_balance, ['posting_date', '<=', $bankAccountPosting->posting_date]);
            $bankAccountPosting->amount = $this->utilitarios::formatReal($data['amount']);
            $bankAccountPosting->type = $data['type'];
            $bankAccountPosting->type_bank_account_posting_id = $data['type_bank_account_posting_id'];
            $bankAccountPosting->bank_account_id = $data['bank_account_id'];
            $bankAccountPosting->income_id = $data['income_id'];
            $bankAccountPosting->expense_id = $data['expense_id'];
            $balance = $this->bankAccountPosting::where('bank_account_id', $bankAccountPosting->bank_account_id)
                ->where($where_balance)
                ->orderBy('posting_date', 'desc')
                ->orderBy('id', 'desc')->first();
            $amount = ($bankAccountPosting->type === 'C' ? $bankAccountPosting->amount : (-$bankAccountPosting->amount));
            if($balance === null){
                $bankAccountPosting->account_balance = $amount;
            }else{
                $bankAccountPosting->account_balance = $balance->account_balance + $amount;
            }
            $bankAccountPosting->save();
            $this->recalcSaldo($bankAccountPosting->posting_date, $bankAccountPosting->bank_account_id);
            $this->db::commit();
            $this->session::flash('message', ['msg' => 'Lançamento Salvo com sucesso', 'type' => 'success']);
            return redirect()->routeTenant('bank_account_posting.index', [$bankAccountPosting->bank_account_id]);
        }catch (Exception $e){
            $this->db::rollBack();
            $this->session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($tenant, $id)
    {
        $bankAccountPosting = BankAccountPosting::find($id);
        return response()->json(["result"=> true, "bankAccountPosting"=> $bankAccountPosting], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update($tenant, Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
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
        }catch(Exception $e){
            DB::rollBack();
            Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->routeTenant('bank_account_posting.file');
        }
    }

    public function readFileStore(Request $request){
        try{
            DB::beginTransaction();
            $filestxt = $request->arquivostxt;
            if(isset($filestxt)){
                foreach($filestxt as $filetxt){
                    $this->readFileTxt(file($filetxt));
                }
            }
            $filesofx = $request->arquivosofx;
            if(isset($filesofx)){
                foreach($filesofx as $fileofx){
                    $this->readFileOfx($fileofx);
                }
            }
            DB::commit();
            Session::flash('message', ['msg' => 'Arquivo(s) Lido(s) Com Sucesso', 'type' => 'success']);
            return redirect()->routeTenant('bank_account_posting.file');
        }catch(Exception $e){
            DB::rollBack();
            Session::flash('message', ['msg' => $e->getMessage(), 'type' => 'danger']);
            return redirect()->routeTenant('bank_account_posting.file');
        }
    }

    function readFileOfx($fileofx){
        $type_bank_account_posting_not_saves = [];
        $ofx = new Ofx($fileofx);
        $bankAccount = $this->mountBankAccountOfx($ofx);
        foreach($ofx->bankTranList as $transactions){
            $typeBankAccountPosting = new TypeBankAccountPosting();
            $bankAccountPosting = $this->mountBankAccountPostingOfx($transactions, $typeBankAccountPosting, $bankAccount);
            if($typeBankAccountPosting->getType((string)$transactions->MEMO) === 0){
                array_push($type_bank_account_posting_not_saves, $transactions->MEMO);
                continue;
            }
            if(sizeof($type_bank_account_posting_not_saves) === 0){
                $bankAccountPosting->save();
            }
        }
        if(sizeof($type_bank_account_posting_not_saves) !== 0){
            throw new Exception('\nExistem tipos não salvos: '.implode(",", $type_bank_account_posting_not_saves));
        }
    }

    function mountBankAccountOfx($ofx){
        $bankAccount = new BankAccount();
        $bank = Bank::where('number', 'like', '%'.(integer)$ofx->bankId.'%')->first();
        $bankAccount->number_account = (string)$ofx->acctId;
        $number_account = substr((string)$ofx->acctId, 0,8);
        $bankAccount = BankAccount::where('bank_id', $bank->id)
            ->where('number_account', 'like', '%'.(integer)$number_account.'%')->first();
        return $bankAccount;
    }

    function mountBankAccountPostingOfx($transactions, TypeBankAccountPosting $typeBankAccountPosting, BankAccount $bankAccount){
        $bankAccountPosting = new BankAccountPosting();
        $keyFileTypeBankAccountPosting = $typeBankAccountPosting->getType((string)$transactions->MEMO);
        $bankAccountPosting->type_bank_account_posting_id = $keyFileTypeBankAccountPosting->type_id;
        $date_post = $transactions->DTPOSTED;
        $bankAccountPosting->posting_date = Carbon::create(substr($date_post, 0,4), substr($date_post, 4,2), substr($date_post, 6,2), substr($date_post, 8,2));
        $bankAccountPosting->bank_account_id = $bankAccount->id;
        $bankAccountPosting->document = $transactions->FITID;
        $bankAccountPosting->amount = ((float)$transactions->TRNAMT < 0) ? -((float)$transactions->TRNAMT) : (float)$transactions->TRNAMT;
        $bankAccountPosting->type = (string)$transactions->TRNTYPE === ofxCredit ? credit : debit;
        $bankAccountPosting->expense_id = $keyFileTypeBankAccountPosting->expense_id;
        $bankAccountPosting->income_id = $keyFileTypeBankAccountPosting->income_id;
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

    function readFileTxt($file){
        $header = explode(';', $file[0]);
        $type_bank_account_posting_not_saves = [];

        $this->validationFile($header);

        foreach($file as $index => $posting){
            if($index === 0){
                continue;
            }
            $data = $this->clearStringFile($posting);
            $typeBankAccountPosting = new TypeBankAccountPosting();
            if($typeBankAccountPosting->getType($data[3]) === 0){
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
            throw new Exception('\nExistem tipos não salvos: '.implode(",", $type_bank_account_posting_not_saves));
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
            throw new Exception('Arquivo inválido');
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
        $keyFileTypeBankAccountPosting = $typeBankAccountPosting->getType($data[3]);
        $bankAccountPosting->type_bank_account_posting_id = $keyFileTypeBankAccountPosting->type_id;
        $bankAccountPosting->expense_id = $keyFileTypeBankAccountPosting->expense_id;
        $bankAccountPosting->income_id = $keyFileTypeBankAccountPosting->income_id;
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
                        $dt_initial = Utilitarios::formatDataCarbon(trim($explode[0]));
                        $dt_final = Utilitarios::formatDataCarbon(trim($explode[1]));
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
                })->addColumn('actions', function($model){
                    return Utilitarios::getBtnAction([
                        ['type'=>'edit', 'url'=>'#', 'id' => $model->id],
                        ['type'=>'delete', 'url' => '', 'id' => $model->id]
                    ]);
                })
                ->rawColumns(['actions'])
                ->toJson();
            return $response->original;
        }catch (Exception $e){
            dd('erro!'.$e->getMessage());
        }
    }
}
