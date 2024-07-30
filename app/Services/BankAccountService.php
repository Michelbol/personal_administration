<?php


namespace App\Services;

use App\Models\BankAccount;
use App\Repositories\BankAccountRepository;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;

class BankAccountService extends CRUDService
{
    /**
     * @var BankAccount
     */
    protected $modelClass = BankAccount::class;

    /**
     * @var BankAccountRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new BankAccountRepository();
    }

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
        return getFormatReal(DB::table('bank_account_postings')
            ->whereBetween('posting_date', [$firstDayYear,$endOfYear])
            ->where('type_bank_account_posting_id', 1)
            ->where('type', 'C')
            ->where('bank_account_id', $bankAccountId)
            ->sum('amount'));
    }

    /**
     * @param Carbon $startAt
     * @param Carbon $endAt
     * @param $bankAccountId
     * @return string
     */
    public function calcMonthlyInterest(Carbon $startAt, Carbon $endAt, $bankAccountId){
        $builder = DB::table('bank_account_postings')
            ->whereBetween('posting_date', [$startAt, $endAt])
            ->where('type_bank_account_posting_id', 1)
            ->where('type', 'C')
            ->where('bank_account_id', $bankAccountId);
            
        $amount = $this->groupByFieldUsingYearAndMonth('posting_date', $builder)
            ->select(DB::raw('sum(amount) as amount'))
            ->get();
        return $amount->implode('amount', ',');
    }

    private function generateGetYearInSql(string $field) {
        if (config('database.default') === 'sqlite') {
            return "strftime('%Y', substr($field,7,4))";
        }
        return "YEAR($field)";
    }

    private function generateGetMonthInSql(string $field) {
        if (config('database.default') === 'sqlite') {
            return "strftime('%Y', substr($field,2,3))";
        }
        return "MONTH($field)";
    }

    private function groupByFieldUsingYearAndMonth(string $field, Builder $builder) {
        return $builder->groupBy(DB::raw($this->generateGetYearInSql($field)), $this->generateGetMonthInSql($field));
    }

    /**
     * @param Carbon $startAt
     * @param Carbon $endAt
     * @param $bankAccountId
     * @return string
     */
    public function calcMonthlyBalance(Carbon $startAt, Carbon $endAt, $bankAccountId){
        $builder = DB::table('bank_account_postings')
            ->whereBetween('posting_date', [$startAt, $endAt])
            ->where('bank_account_id', $bankAccountId)
            ->orderBy('posting_date', 'desc');

        $balance = $this->groupByFieldUsingYearAndMonth('posting_date', $builder)
            ->select(DB::raw('COALESCE(account_balance, 0) as account_balance'))
            ->get();
        return $balance->implode('account_balance',',');
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
        return isset($accountBalancePosting) ? getFormatReal($accountBalancePosting->account_balance) : '0,00';
    }

    /**
     * @return BankAccount[]|Collection
     */
    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getExpensesByBankAccountMonthly(Carbon $startAt, Carbon $endAt, int $bankAccountId)
    {
        return $this->repository->getExpensesByBankAccountMonthly($startAt, $endAt, $bankAccountId);
    }

    public function fixReport($expenses, Carbon $startAt, Carbon $endAt)
    {
        $diff = $startAt->diffInMonths($endAt);
        /**
         * @var $expense Collection
         * @var $expenses Collection
         */
        foreach ($expenses as $key => $expense){
            $count = $diff;
            while ($count >= 0){
                if(!$expense->has($count)){
                    $expense[$count] = 0;
                }
                $count--;
            }
            $expenses[$key] = $expense->sortKeys();
        }
        return $expenses;
    }
}
