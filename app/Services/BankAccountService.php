<?php


namespace App\Services;

use DB;
use Exception;
use App\Utilitarios;
use App\Models\BankAccount;
use Illuminate\Support\Carbon;

class BankAccountService extends CRUDService
{
    /**
     * @var BankAccount
     */
    protected $modelClass = BankAccount::class;

    /**
     * @param BankAccount $model
     * @param array $data
     */
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
     * @return BankAccount
     * @throws Exception
     */
    public function create($data)
    {
        /**
         * @var $model BankAccount
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

    /**
     * @param BankAccount | string $model
     *
     * @throws Exception
     */
    public function delete($model)
    {
        /**
         * @var $model BankAccount
         */
        $model = $this->findById($model);
        $model->bankAccountPostings()->delete();
        $model->delete();
    }

    /**
     * @param $bankAccountId
     * @param $year
     * @return string
     */
    public function calcAnnualInterest($bankAccountId, $year){
        $firstDayYear = Carbon::create($year)->firstOfYear();
        $endOfYear = Carbon::create($year)->lastOfYear();
        return Utilitarios::getFormatReal(DB::table('bank_account_postings')
            ->whereBetween('posting_date', [$firstDayYear,$endOfYear])
            ->where('type_bank_account_posting_id', 1)
            ->where('type', 'C')
            ->where('bank_account_id', $bankAccountId)
            ->sum('amount'));
    }

    /**
     * @param $year
     * @param $bankAccountId
     * @return string
     */
    public function calcMonthlyInterest($year, $bankAccountId){
        $interestMonthly = [];
        for($i = 1; $i <=12; $i++){
            $interestMonthly[$i] = DB::table('bank_account_postings')
                ->whereBetween('posting_date', [Carbon::create($year, $i, 1),Carbon::create($year,$i)->endOfMonth()])
                ->where('type_bank_account_posting_id', 1)
                ->where('type', 'C')
                ->where('bank_account_id', $bankAccountId)
                ->sum('amount');
        }
        return collect($interestMonthly)->implode(',');
    }

    /**
     * @param $year
     * @param $bankAccountId
     * @return string
     */
    public function calcMonthlyBalance($year, $bankAccountId){
        $balanceMonthly = [];
        for($i = 1; $i <=12; $i++){
            $balance = DB::table('bank_account_postings')
                ->whereBetween('posting_date', [Carbon::create($year, $i, 1),Carbon::create($year,$i)->endOfMonth()])
                ->where('bank_account_id', $bankAccountId)
                ->orderBy('posting_date', 'desc')->first();
            $balanceMonthly[$i] = isset($balance) ? $balance->account_balance/100 : 0;
        }
        return collect($balanceMonthly)->implode(',');
    }

    /**
     * @param $bankAccountId
     * @return string
     */
    public function lastBalance($bankAccountId){
        $accountBalancePosting = DB::table('bank_account_postings')
            ->where('bank_account_id', $bankAccountId)
            ->orderBy('posting_date','desc')
            ->orderBy('id','desc')->first();
        return isset($accountBalancePosting) ? Utilitarios::getFormatReal($accountBalancePosting->account_balance) : '0,00';
    }
}
