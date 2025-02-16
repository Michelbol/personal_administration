<?php


namespace App\Repositories;

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Expenses;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Collection;

class BankAccountRepository
{
    /**
     * @return BankAccount[]|Collection
     */
    public function getAll()
    {
        return BankAccount::all();
    }

    public function getExpensesByBankAccountMonthly(Carbon $startAt, Carbon $endAt, int $bankAccountId)
    {
        return DB
            ::table('expenses as e')
            ->join('bank_account_postings as bap', 'e.id', 'bap.expense_id')
            ->join('bank_accounts as ba', 'ba.id', 'bap.bank_account_id')
            ->whereBetween('bap.posting_date', [$startAt, $endAt])
            ->where('ba.id', $bankAccountId)
            ->groupBy('e.id', DB::raw($this->generateGetYearInSql('bap.posting_date')), DB::raw($this->generateGetMonthInSql('bap.posting_date')))
            ->select(
                DB::raw('sum(bap.amount) as total_amount'),
                'e.name',
                DB::raw($this->generateGetMonthInSql('bap.posting_date').'-1 as month')
            )
            ->get();
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

    public function findBankAccountByBankAndAccountNumber(Bank $bank, int $number_account): BankAccount
    {
        return BankAccount
            ::whereBankId($bank->id)
            ->where('number_account', 'like', "$number_account")
            ->first();
    }
}
