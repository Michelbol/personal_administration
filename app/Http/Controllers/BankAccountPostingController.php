<?php

namespace App\Http\Controllers;

use App\BankAccountPosting\BankAccountPostingOfxFileReader;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\BankAccountPosting;
use App\Models\Enum\TypeBankAccountPostingEnum;
use App\Models\Expenses;
use App\Models\Income;
use App\Models\KeyFileTypeBankAccountPosting;
use App\Models\TypeBankAccountPosting;
use App\Ofx;
use App\Services\BankAccountPostingService;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class BankAccountPostingController extends CrudController
{
    /**
     * @var BankAccountPostingService
     */
    protected $service = BankAccountPostingService::class;

    public function __construct(
        private BankAccountPostingOfxFileReader $bankAccountPostingOfxFileReader,
        BankAccountPosting                      $bankAccountPosting,
        BankAccountPostingService               $service,
    )
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
        $data['amount'] = formatReal($data['amount']);
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
                ->orderBy('posting_date')
                ->orderBy('id')
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
        $filesTxt = $request->file('arquivostxt');
        if (isset($filesTxt)) {
            try{
                DB::beginTransaction();
                foreach ($filesTxt as $fileTxt) {
                    $this->readFileTxt(file($fileTxt));
                }
                DB::commit();
            }catch (Exception $exception){
                DB::rollBack();
                $this->errorMessage($exception->getMessage());
                return redirect()->back();
            }
        }
        $filesOfx = $request->file('arquivosofx');
        if (isset($filesOfx)) {
            try{
                $this->bankAccountPostingOfxFileReader->readFiles($filesOfx);
            }catch (Exception $exception){
                DB::rollBack();
                $this->errorMessage($exception->getMessage());
                return redirect()->back();
            }
        }
        $this->successMessage("Arquivo(s) Lido(s) Com Sucesso");
        return redirect()->routeTenant('bank_account_posting.file');
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
            $this->clearString($header[5]) === '"Deb_Cred"') {
        } else {
            throw new Exception('Arquivo inválido');
        }
    }

    public function clearString(string $str)
    {
        return str_replace("\r", '', str_replace("\n", '', $str));
    }

    /**
     * @param $data
     * @return Builder|Model|object|BankAccount
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

    /**
     * @param $data
     * @param BankAccount $bankAccount
     * @param TypeBankAccountPosting $typeBankAccountPosting
     * @return BankAccountPosting
     */
    function mountBankAccountPosting($data, BankAccount $bankAccount, TypeBankAccountPosting $typeBankAccountPosting)
    {
        $bankAccountPosting = new BankAccountPosting();
        $keyFileTypeBankAccountPosting = $typeBankAccountPosting->getType($data[3]);
        $bankAccountPosting->type_bank_account_posting_id = $keyFileTypeBankAccountPosting->type_id;
        $bankAccountPosting->expense_id = $keyFileTypeBankAccountPosting->expense_id;
        $bankAccountPosting->income_id = $keyFileTypeBankAccountPosting->income_id;
        $bankAccountPosting->posting_date = Carbon::create(substr($data[1], 0, 4), substr($data[1], 4, 2), substr($data[1], 6, 2))->format('d/m/Y H:i');
        $bankAccountPosting->bank_account_id = $bankAccount->id;
        $bankAccountPosting->document = $data[2];
        $bankAccountPosting->amount = $data[4];
        $bankAccountPosting->type = $this->clearString($data[5]);
        return $this->service->calcAccountBalance($bankAccountPosting);
    }

    /**
     * @param $posting
     * @return array
     */
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

    /**
     * @return Factory|View
     */
    public function file()
    {
        return view('bank_account_posting.file');
    }

    /**
     * @param Request $request
     * @param $tenant
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function get(Request $request, $tenant, $id)
    {
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
                    $dt_initial = formatDataCarbon(trim($explode[0]));
                    $dt_final = formatDataCarbon(trim($explode[1]));
                    $query->whereBetween('posting_date', [$dt_initial, $dt_final]);
                }
            })
            ->addColumn('posting_date', function ($model) {
                return formatDataCarbon($model->posting_date)->format('d/m/Y H:i');
            })
            ->addColumn('amount', function ($model) {
                return 'R$: ' . getFormatReal($model->amount);
            })
            ->addColumn('type', function ($model) {
                return $model->type === 'C' ? 'Crédito' : 'Débito';
            })->addColumn('account_balance', function ($model) {
                return 'R$: ' . getFormatReal($model->account_balance);
            })->addColumn('actions', function ($model) {
                return getBtnAction([
                    ['type' => 'edit', 'url' => '#', 'id' => $model->id],
                    ['type' => 'delete', 'url' => routeTenant('bank_account_posting.destroy', [$model->id]), 'id' => $model->id]
                ]);
            })
            ->rawColumns(['actions'])
            ->toJson();
        return $response->original;
    }
}
