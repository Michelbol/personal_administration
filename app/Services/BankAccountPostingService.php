<?php


namespace App\Services;

use App\Models\BankAccountPosting;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BankAccountPostingService extends CRUDService
{
    /**
     * @var BankAccountPosting
     */
    protected $modelClass = BankAccountPosting::class;

    const STR_OPENING_ACCOUNT = 'Abertura Conta';

    /**
     * @param array $data
     * @return BankAccountPosting|Model
     * @throws Exception
     */
    public function create($data)
    {
        if(isset($data['new_income'])){
            $data['income_id'] = $this->createIncome(['name' => $data['new_income'], 'amount' => $data['amount']]);
        }
        if(isset($data['new_expense'])){
            $data['expense_id'] = $this->createExpense(['name' => $data['new_expense'], 'amount' => $data['amount']]);
        }
        $model = new BankAccountPosting();
        $this->fill($model, $data);
        $model->save();

        return $model;
    }

    /**
     * @param BankAccountPosting $model
     * @param array $data
     */
    public function fill(&$model, $data)
    {
        $model->document = $data['document'];
        $model->posting_date = $data['posting_date'];
        $model->amount = $data['amount'];
        $model->type = $data['type'];
        $model->type_bank_account_posting_id = $data['type_bank_account_posting_id'];
        $model->account_balance = $data['account_balance'];
        $model->bank_account_id = $data['bank_account_id'];
        $model->income_id = $data['income_id'] ?? null;
        $model->expense_id = $data['expense_id'] ?? null;
    }

    /**
     * @param array $data
     * @return BankAccountPosting
     * @throws Exception
     */
    public function createOpening(array $data)
    {
        /**
         * @var $model BankAccountPosting
         */
        $model = new $this->modelClass();
        $data['document'] = self::STR_OPENING_ACCOUNT;
        $data['posting_date'] = Carbon::now();
        $data['amount'] = $data['account_balance'];
        $data['type'] = 'credit';
        $data['type_bank_account_posting_id'] = 1;
        $this->fill($model, $data);
        $model->save();

        return $model;
    }

    public function calcBalance(array $data)
    {
        $where = [
            ['bank_account_id', '=', $data['bank_account_id']],
            ['posting_date', '<=', formatDataCarbon($data['posting_date'])]
        ];
        if(isset($data['id'])){
            $where[] = ['id', '!=', $data['id']];
        }
        $balance = BankAccountPosting::where('bank_account_id', $data['bank_account_id'])
            ->where($where)
            ->orderBy('posting_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();
        $amt = 0;
        if(isset($balance)){
            $amt = $balance->account_balance;
        }
        $amount = ($data['type'] === 'C' ? $data['amount'] : (-$data['amount']));
        return $amt + $amount;
    }

    /**
     * @param array $data
     * @throws Exception
     */
    public function createIncome(array $data)
    {
        $data['income_id'] = (new IncomeService())->create($data)->id;
    }

    /**
     * @param array $data
     * @throws Exception
     */
    public function createExpense(array $data)
    {
        $data['expense_id'] = (new ExpenseService())->create($data)->id;
    }

    /**
     * @param BankAccountPosting $bankAccountPosting
     * @return BankAccountPosting|Builder|Model|object|null
     */
    public function lastPosting(BankAccountPosting $bankAccountPosting)
    {
        return BankAccountPosting
            ::whereBankAccountId($bankAccountPosting->bank_account_id)
            ->where('posting_date', '<=', $bankAccountPosting->posting_date)
            ->orderBy('posting_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();
    }

    public function calcAccountBalance(BankAccountPosting $bankAccountPosting)
    {
        $balance = $this->lastPosting($bankAccountPosting);
        if ($balance === null) {
            $bankAccountPosting->account_balance = $bankAccountPosting->amount;
        } else {
            $bankAccountPosting->account_balance = $balance->account_balance +
                ($bankAccountPosting->type === 'C' ? $bankAccountPosting->amount : (-$bankAccountPosting->amount));

        }
        return $bankAccountPosting;
    }
}
