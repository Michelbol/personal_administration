<?php


namespace App\Services;

use App\Models\Income;

class IncomeService extends CRUDService
{
    /**
     * @var Income
     */
    protected $modelClass = Income::class;

    /**
     * @param Income $model
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
