<?php


namespace App\Repositories;

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
            ->groupBy('e.id', DB::raw('YEAR(bap.posting_date)'), DB::raw('MONTH(bap.posting_date)'))
            ->select(
                DB::raw('sum(bap.amount) as total_amount'),
                'e.name',
                DB::raw('MONTH(posting_date)-1 as month'))
            ->get();
    }
}
