<?php

namespace App\Models;

use App\Scopes\TenantModels;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * App\Models\BudgetFinancial
 *
 * @property int $id
 * @property int $month
 * @property string $year
 * @property int $isFinalized
 * @property float $initial_balance
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $tenant_id
 * @property-read Collection|BudgetFinancialPosting[] $budgetFinancialPostings
 * @property-read int|null $budget_financial_postings_count
 * @property-read Collection|BudgetFinancialPosting[] $budgetFinancialPostingsExpenses
 * @property-read int|null $budget_financial_postings_expenses_count
 * @property-read Collection|BudgetFinancialPosting[] $budgetFinancialPostingsIncomes
 * @property-read int|null $budget_financial_postings_incomes_count
 * @method static Builder|BudgetFinancial newModelQuery()
 * @method static Builder|BudgetFinancial newQuery()
 * @method static Builder|BudgetFinancial query()
 * @method static Builder|BudgetFinancial whereCreatedAt($value)
 * @method static Builder|BudgetFinancial whereId($value)
 * @method static Builder|BudgetFinancial whereInitialBalance($value)
 * @method static Builder|BudgetFinancial whereIsFinalized($value)
 * @method static Builder|BudgetFinancial whereMonth($value)
 * @method static Builder|BudgetFinancial whereTenantId($value)
 * @method static Builder|BudgetFinancial whereUpdatedAt($value)
 * @method static Builder|BudgetFinancial whereUserId($value)
 * @method static Builder|BudgetFinancial whereYear($value)
 * @mixin Eloquent
 * @mixin BudgetFinancial
 */
class BudgetFinancial extends Model
{
    use TenantModels;

    protected $fillable = [
        'month',
        'year',
        'isFinalized',
        'initial_balance',
        'user_id'
    ];
    protected $months = [
        1   => 'Janeiro',
        2   => 'Fevereiro',
        3   => 'Março',
        4   => 'Abril',
        5   => 'Maio',
        6   => 'Junho',
        7   => 'Julho',
        8   => 'Agosto',
        9   => 'Setembro',
        10  => 'Outubro',
        11  => 'Novembro',
        12  => 'Dezembro'
    ];

    public function month($index){
        return $this->months[$index];
    }

    /**
     * @return HasMany
     */
    public function budgetFinancialPostings(){
        return $this->hasMany(BudgetFinancialPosting::class);
    }

    /**
     * @return HasMany
     */
    public function budgetFinancialPostingsIncomes(){
        return $this->hasMany(BudgetFinancialPosting::class)->where('income_id', '>', 0);
    }

    /**
     * @return HasMany
     */
    public function budgetFinancialPostingsExpenses(){
        return $this->hasMany(BudgetFinancialPosting::class)->where('expense_id', '>', 0);
    }
}
