<?php


namespace App\Services;

use Carbon\Carbon;
use Exception;
use App\Models\BankAccount;
use Illuminate\Database\Eloquent\Model;

class BankAccountService extends CRUDService
{
    protected $modelClass = BankAccount::class;

    public function fill(&$model, $data)
    {
        $model->name = $data['name'];
        $model->agency = $data['agency'];
        $model->digit_agency = $data['digit_agency'];
        $model->number_account = $data['number_account'];
        $model->digit_account = $data['digit_account'];
        $model->bank_id = $data['bank_id'];
    }

    /**
     * @param array $data
     *
     * @return Model
     * @throws Exception
     */
    public function create($data)
    {
        /**
         * @var $model Model
         */
        $model = new $this->modelClass();
        $this->fill($model, $data);
        $model->save();
        if(isset($data['account_balance'])){
            (new BankAccountPostingService())->createOpening([
                'account_balance' => $data['account_balance'],
                'bank_account_id' => $model->id,
            ]);
        }

        return $model;
    }
}
