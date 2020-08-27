<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankAccount\ExpenseReportRequest;
use App\Models\BankAccount;
use App\Models\Expenses;
use App\Services\BankAccountService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class BankAccountController extends CrudController
{
    /**
     * @var BankAccount
     */
    protected $bankAccount;

    /**
     * @var string
     */
    protected $msgStore = 'Banco Salvo com sucesso';

    /**
     * @var string
     */
    protected $msgUpdate = 'Banco Atualizado com sucesso';

    /**
     * @var string
     */
    protected $msgDestroy = 'Banco Deletado com sucesso';

    /**
     * @var BankAccountService
     */
    protected $service;

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
        $startAt = Carbon::now()->startOfYear();
        $endAt = Carbon::now();
        if($request->has('period_date') && $request->has('period_date')){
            $date = explode(' - ', $request->get('period_date'));
            $startAt = Carbon::createFromFormat('d/m/Y', $date[0]);
            $endAt = Carbon::createFromFormat('d/m/Y', $date[1]);
        }
        $bank_account = $this->bankAccount::find($id);
        $last_balance = $this->service->lastBalance($id);
        $monthInterest = $this->service->calcMonthlyInterest($startAt, $endAt, $id);
        $monthBalance = $this->service->calcMonthlyBalance($startAt, $endAt, $id);
        $endAt = $endAt->format('d/m/Y');
        $startAt = $startAt->format('d/m/Y');
        return view('bank_account.edit',
            compact(
                'bank_account',
                'id',
                'last_balance',
                'monthInterest',
                'startAt',
                'endAt',
                'monthBalance'
            ));
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

    public function reportExpense(ExpenseReportRequest $request)
    {
        $data = $request->validated();
        $startAt = Carbon::now()->startOfYear();
        $endAt = Carbon::now();
        $selectedBank = null;
        if($request->has('period_date')){
            $date = explode(' - ', $data['period_date']);
            $startAt = Carbon::createFromFormat('d/m/Y', $date[0]);
            $endAt = Carbon::createFromFormat('d/m/Y', $date[1]);
        }
        $bankAccounts = $this->service->getAll();
        $expenses = null;
        if($request->has('bank_account_id')){
            $selectedBank = (int) $data['bank_account_id'];
            $expenses = $this
                ->service
                ->getExpensesByBankAccountMonthly($startAt, $endAt, $selectedBank)
                ->groupBy('name')
                ->map(function ($value){
                    return $value->pluck('total_amount', 'month');
                });
            $expenses = $this->service->fixReport($expenses, $startAt, $endAt);
        }

        $endAt = $endAt->format('d/m/Y');
        $startAt = $startAt->format('d/m/Y');
        return view(
            'bank_account.report_expense',
            compact(
                'bankAccounts',
                'selectedBank',
                'endAt',
                'startAt',
                'expenses'
            )
        );
    }
}
