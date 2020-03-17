<?php


namespace App\Services;

use App\Models\BankAccountPosting;
use Carbon\Carbon;
use Exception;
use App\Models\BankAccount;
use Illuminate\Database\Eloquent\Model;

class BankAccountPostingService extends CRUDService
{
    /**
     * @var BankAccountPosting
     */
    protected $modelClass = BankAccountPosting::class;

    const STR_OPENING_ACCOUNT = 'Abertura Conta';

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
        $data['type'] = credit;
        $data['type_bank_account_posting_id'] = 1;
        $this->fill($model, $data);
        $model->save();

        return $model;
    }
}
