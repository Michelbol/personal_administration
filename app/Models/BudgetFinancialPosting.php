<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetFinancialPosting extends Model
{
    protected $fillable = [
      'id',
      'posting_date',
      'amount',
      'account_balance',
      'income_id',
      'expense_id',
      'budget_financial_id',
    ];
}
