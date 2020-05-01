<?php


namespace App\Services;

use App\Models\Car;
use App\Models\Expenses;

class ExpenseService extends CRUDService
{
    /**
     * @var Expenses
     */
    protected $modelClass = Expenses::class;

    /**
     * @param Expenses $model
     * @param array $data
     */
    public function fill(&$model, $data)
    {
        $model->name = $data['name'];
        $model->amount = $data['amount'] ?? null;
        $model->isFixed = $data['isFixed'] ?? false;
        $model->due_date = $data['due_date'] ?? null;
    }
}
