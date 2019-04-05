<?php

namespace App\Models;

use App\Scopes\TenantModels;
use Illuminate\Database\Eloquent\Model;

class BudgetFinancial extends Model
{
    use TenantModels;

    protected $fillable = [
        'month',
        'year',
        'isFinalized',
        'initial_balance'
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

    public function budgetFinancialPostings(){
        return $this->hasMany(BudgetFinancialPosting::class);
    }

    public function budgetFinancialPostingsIncomes(){
        return $this->hasMany(BudgetFinancialPosting::class)->where('income_id', '>', 0);
    }
    public function budgetFinancialPostingsExpenses(){
        return $this->hasMany(BudgetFinancialPosting::class)->where('expense_id', '>', 0);
    }
}
