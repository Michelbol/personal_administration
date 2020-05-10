<?php

namespace App\Http\Controllers;

use App\Services\BankAccountPostingService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
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
use DB;
use Illuminate\Http\RedirectResponse;
use App\Models\TypeBankAccountPosting;
use Illuminate\Database\Eloquent\Builder;
use App\Models\KeyFileTypeBankAccountPosting;

class BankAccountPostingController extends CrudController
{
    /**
     * @var BankAccountPostingService
     */
    protected $service = BankAccountPostingService::class;

    public function __construct(BankAccountPosting $bankAccountPosting,
                                BankAccountPostingService $service)
    {
        parent::__construct($service, $bankAccountPosting);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $tenant
     * @param $id
     * @return Factory|View
     */
    public function indexPostingByBank($tenant, $id)
    {
        return view
        (
            'bank_account_posting.index',
            [
                'bankAccount' => BankAccount::find($id),
                'filterTypeBankAccountPostings' => TypeBankAccountPosting::all(),
                'incomes' => Income::all(['id', 'name']),
                'expenses' => Expenses::all(['id', 'name'])
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response|RedirectResponse
     * @throws Exception
     */
    public function store(Request $request)
    {
        /**
         * @var $bankAccountPosting BankAccountPosting
         */
        $data = $request->all();
        $data['amount'] = formatReal($data['amount']);
        $data['account_balance'] = $this->service->calcBalance($data);
        $bankAccountPosting = $this->service->create($data);

        $this->recalcSaldo($bankAccountPosting->posting_date, $bankAccountPosting->bank_account_id);
        $this->successMessage('Lançamento Salvo com sucesso');
        return redirect()->routeTenant('bank_account_posting.index', [$bankAccountPosting->bank_account_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param $tenant
     * @return JsonResponse
     */
    public function show($tenant, $id)
    {
        return response()->json
        (
            [
                "result" => true,
                "bankAccountPosting" => BankAccountPosting::find($id)
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $tenant
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function update($tenant, Request $request, $id)
    {
        /**
         * @var $bankAccountPosting BankAccountPosting
         */
        $data = $request->all();
        $data['account_balance'] = $this->service->calcBalance($data);
        $bankAccountPosting = $this->service->update(BankAccountPosting::find($id), $data);

        $this->recalcSaldo($bankAccountPosting->posting_date, $bankAccountPosting->bank_account_id);
        $this->successMessage('Lançamento Salvo com sucesso');
        return redirect()->routeTenant('bank_account_posting.index', [$bankAccountPosting->bank_account_id]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $tenant
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy($tenant, Request $request, $id)
    {
        $bankAccountPosting = BankAccountPosting::findOrFail($id);
        $bankAccountPosting->delete();

        $balance = $this->service->lastPosting($bankAccountPosting);
        $this->recalcSaldo(
            isset($balance->posting_date)
                ? formatDataCarbon($balance->posting_date)
                : Carbon::createFromTimestamp(-1),
            $bankAccountPosting->bank_account_id
        );
        $this->successMessage("Lançamento deletado com sucesso");
        return redirect()->back();
    }

    /**
     * @param Carbon $date
     * @param $bank_account_id
     * @return bool
     * @throws Exception
     */
    public function recalcSaldo(Carbon $date, $bank_account_id)
    {
        try {
            DB::beginTransaction();
            $itens_recalc = BankAccountPosting
                ::whereBankAccountId($bank_account_id)
                ->where('posting_date', '>=', $date)
                ->orderBy('posting_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();
            $balance = $itens_recalc->first()->account_balance;
            $itens_recalc->shift();
            foreach ($itens_recalc as $item) {
                $balance = $balance +
                    ($item->type === 'C'
                        ? $item->amount
                        : (-$item->amount));
                $item->account_balance = $balance;
                $item->save();
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            $this->errorMessage($e->getMessage());
            return redirect()->routeTenant('bank_account_posting.file');
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function readFileStore(Request $request)
    {
        $filesTxt = $request->get('arquivostxt');
        if (isset($filesTxt)) {
            foreach ($filesTxt as $fileTxt) {
                $this->readFileTxt(file($fileTxt));
            }
        }
        $filesOfx = $request->get('arquivosofx');
        if (isset($filesOfx)) {
            foreach ($filesOfx as $fileOfx) {
                $this->readFileOfx($fileOfx);
            }
        }
        $this->successMessage("Arquivo(s) Lido(s) Com Sucesso");
        return redirect()->routeTenant('bank_account_posting.file');
    }

    /**
     * @param $fileOfx
     * @throws Exception
     */
    function readFileOfx($fileOfx)
    {
        $type_bank_account_posting_not_saves = [];
        $ofx = new Ofx($fileOfx);
        $bankAccount = $this->mountBankAccountOfx($ofx);
        foreach ($ofx->bankTranList as $transactions) {
            $typeBankAccountPosting = new TypeBankAccountPosting();
            $bankAccountPosting = $this->mountBankAccountPostingOfx($transactions, $typeBankAccountPosting, $bankAccount);
            if ($typeBankAccountPosting->getType((string)$transactions->MEMO) === 0) {
                array_push($type_bank_account_posting_not_saves, $transactions->MEMO);
                continue;
            }
            if (sizeof($type_bank_account_posting_not_saves) === 0) {
                $bankAccountPosting->save();
            }
        }
        if (sizeof($type_bank_account_posting_not_saves) !== 0) {
            throw new Exception('\nExistem tipos não salvos: ' . implode(",", $type_bank_account_posting_not_saves));
        }
    }

    /**
     * @param $ofx
     * @return BankAccount
     */
    function mountBankAccountOfx($ofx)
    {
        $bankAccount = new BankAccount();
        $bank = Bank
            ::where(
                'number',
                'like',
                '%'.(integer)$ofx->bankId.'%')
            ->first();
        $bankAccount->number_account = (string)$ofx->acctId;
        $number_account = substr((string)$ofx->acctId, 0, 8);
        $bankAccount = BankAccount
            ::whereBankId($bank->id)
            ->where(
                'number_account',
                'like',
                '%'.(integer)$number_account.'%')
            ->first();
        return $bankAccount;
    }

    /**
     * @param $transactions
     * @param TypeBankAccountPosting $typeBankAccountPosting
     * @param BankAccount $bankAccount
     * @return BankAccountPosting
     */
    function mountBankAccountPostingOfx($transactions, TypeBankAccountPosting $typeBankAccountPosting, BankAccount $bankAccount)
    {
        $bankAccountPosting = new BankAccountPosting();
        $keyFileTypeBankAccountPosting = $typeBankAccountPosting->getType((string)$transactions->MEMO);
        if ($keyFileTypeBankAccountPosting === 0) {
            $keyFileTypeBankAccountPosting = new KeyFileTypeBankAccountPosting();
            $keyFileTypeBankAccountPosting->type_id = 0;
            $keyFileTypeBankAccountPosting->expense_id = 0;
            $keyFileTypeBankAccountPosting->income_id = 0;
        }
        $bankAccountPosting->type_bank_account_posting_id = $keyFileTypeBankAccountPosting->type_id;
        $date_post = $transactions->DTPOSTED;
        $bankAccountPosting->posting_date = Carbon::create(substr($date_post, 0, 4), substr($date_post, 4, 2), substr($date_post, 6, 2), substr($date_post, 8, 2));
        $bankAccountPosting->bank_account_id = $bankAccount->id;
        $bankAccountPosting->document = $transactions->FITID;
        $bankAccountPosting->amount = ((float)$transactions->TRNAMT < 0) ? -((float)$transactions->TRNAMT) : (float)$transactions->TRNAMT;
        $bankAccountPosting->type = (string)$transactions->TRNTYPE === ofxCredit ? credit : debit;
        $bankAccountPosting->expense_id = $keyFileTypeBankAccountPosting->expense_id;
        $bankAccountPosting->income_id = $keyFileTypeBankAccountPosting->income_id;
        return $this->calcAccountBalance($bankAccountPosting);
    }

    public function calcAccountBalance(BankAccountPosting $bankAccountPosting)
    {
        $balance = $this->service->lastPosting($bankAccountPosting);
        if ($balance === null) {
            $bankAccountPosting->account_balance = $bankAccountPosting->amount;
        } else {
            $bankAccountPosting->account_balance = $balance->account_balance +
                ($bankAccountPosting->type === 'C' ? $bankAccountPosting->amount : (-$bankAccountPosting->amount));

        }
        return $bankAccountPosting;
    }

    /**
     * @param $file
     * @throws Exception
     */
    function readFileTxt($file)
    {
        $header = explode(';', $file[0]);
        $type_bank_account_posting_not_saves = [];

        $this->validationFile($header);

        foreach ($file as $index => $posting) {
            if ($index === 0) {
                continue;
            }
            $data = $this->clearStringFile($posting);
            $typeBankAccountPosting = new TypeBankAccountPosting();
            if ($typeBankAccountPosting->getType($data[3]) === 0) {
                array_push($type_bank_account_posting_not_saves, $data[3]);
                continue;
            }
            $bankAccount = $this->mountBankAccount($data);
            $bankAccountPosting = $this->mountBankAccountPosting($data, $bankAccount, $typeBankAccountPosting);
            if (sizeof($type_bank_account_posting_not_saves) === 0) {
                $bankAccountPosting->save();
            }
        }
        if (sizeof($type_bank_account_posting_not_saves) !== 0) {
            throw new Exception('\nExistem tipos não salvos: ' . implode(",", $type_bank_account_posting_not_saves));
        }
    }

    /**
     * @param $header
     * @throws Exception
     */
    function validationFile($header)
    {
        if ($header[0] === '"Conta"' &&
            $header[1] === '"Data_Mov"' &&
            $header[2] === '"Nr_Doc"' &&
            $header[3] === '"Historico"' &&
            $header[4] === '"Valor"' &&
            str_replace("\n", '', $header[5]) === '"Deb_Cred"') {
        } else {
            throw new Exception('Arquivo inválido');
        }
    }

    /**
     * @param $data
     * @return BankAccount
     */
    function mountBankAccount($data)
    {
        return BankAccount
            ::where
            (
                [
                    'agency' => intval(substr($data[0], 0, 4)),
                    'operation' => intval(substr($data[0], 4, 3)),
                    'number_account' => intval(substr($data[0], 7, 8)),
                    'digit_account' => intval(substr($data[0], 15, 1))
                ]
            )
            ->first();
    }

    function mountBankAccountPosting($data, BankAccount $bankAccount, TypeBankAccountPosting $typeBankAccountPosting)
    {
        $bankAccountPosting = new BankAccountPosting();
        $keyFileTypeBankAccountPosting = $typeBankAccountPosting->getType($data[3]);
        $bankAccountPosting->type_bank_account_posting_id = $keyFileTypeBankAccountPosting->type_id;
        $bankAccountPosting->expense_id = $keyFileTypeBankAccountPosting->expense_id;
        $bankAccountPosting->income_id = $keyFileTypeBankAccountPosting->income_id;
        $bankAccountPosting->posting_date = Carbon::create(substr($data[1], 0, 4), substr($data[1], 4, 2), substr($data[1], 6, 2));
        $bankAccountPosting->bank_account_id = $bankAccount->id;
        $bankAccountPosting->document = $data[2];
        $bankAccountPosting->amount = $data[4];
        $bankAccountPosting->type = str_replace("\n", '', $data[5]);
        return $this->calcAccountBalance($bankAccountPosting);
    }

    function clearStringFile($posting)
    {
        $data = explode(';', $posting);
        $data[0] = str_replace('"', '', $data[0]);
        $data[1] = str_replace('"', '', $data[1]);
        $data[2] = str_replace('"', '', $data[2]);
        $data[3] = str_replace('"', '', $data[3]);
        $data[4] = str_replace('"', '', $data[4]);
        $data[5] = str_replace('"', '', $data[5]);
        return $data;
    }

    public function file()
    {
        return view('bank_account_posting.file');
    }

    public function get(Request $request, $tenant, $id)
    {
        try {
            $model = BankAccountPosting
                ::whereBankAccountId($id)
                ->join(
                    'type_bank_account_postings',
                    'type_bank_account_postings.id',
                    'bank_account_postings.type_bank_account_posting_id')
                ->select(
                    [
                        'bank_account_postings.id',
                        'document',
                        'posting_date',
                        'amount',
                        'type',
                        'type_bank_account_postings.name as type_name',
                        'account_balance'
                    ]
                )
                ->orderBy('posting_date', 'desc')
                ->orderBy('bank_account_postings.id', 'desc');

            $response = DataTables::of($model)
                ->filter(function (Builder $query) use ($request) {

                    if ($request->get('type_name') > 0) {
                        $query->where('type_bank_account_postings.id', $request->get('type_name'));
                    }
                    if ($request->get('type') !== "0") {
                        $query->where('type', $request->get('type'));
                    }
                    if ($request->get('posting_date') !== null) {
                        $explode = explode('-', $request->get('posting_date'));
                        $dt_initial = Utilitarios::formatDataCarbon(trim($explode[0]));
                        $dt_final = Utilitarios::formatDataCarbon(trim($explode[1]));
                        $query->whereBetween('posting_date', [$dt_initial, $dt_final]);
                    }
                })
                ->addColumn('posting_date', function ($model) {
                    return Utilitarios::formatDataCarbon($model->posting_date)->format('d/m/Y H:i');
                })
                ->addColumn('amount', function ($model) {
                    return 'R$: ' . Utilitarios::getFormatReal($model->amount);
                })
                ->addColumn('type', function ($model) {
                    return $model->type === 'C' ? 'Crédito' : 'Débito';
                })->addColumn('account_balance', function ($model) {
                    return 'R$: ' . Utilitarios::getFormatReal($model->account_balance);
                })->addColumn('actions', function ($model) {
                    return Utilitarios::getBtnAction([
                        ['type' => 'edit', 'url' => '#', 'id' => $model->id],
                        ['type' => 'delete', 'url' => routeTenant('bank_account_posting.destroy', [$model->id]), 'id' => $model->id]
                    ]);
                })
                ->rawColumns(['actions'])
                ->toJson();
            return $response->original;
        } catch (Exception $e) {
            dd('erro!' . $e->getMessage());
        }
    }
}
