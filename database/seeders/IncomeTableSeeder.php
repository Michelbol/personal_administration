<?php

namespace Database\Seeders;

use App\Models\Income;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class IncomeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tenant = Tenant::first();
        $this->createIncome('Qualquer coisa', 11, false, 0, 31, $tenant->id);
        $this->createIncome('Transferências Minhas Contas', 12, false, 0, 31, $tenant->id);
        $this->createIncome('Juros', 4, false, 0, 31, $tenant->id);
        $this->createIncome('Transferência', 2, false, 30, null, $tenant->id);
        $this->createIncome('Salário', 1, true, 2300, 8, $tenant->id);
        $this->createIncome('FGTS', 6, false, 0, 31, $tenant->id);
        $this->createIncome('PIS', 7, false, 0, 31, $tenant->id);

    }

    public function createIncome(string $name, int $id, bool $isFixed, float $amount, $dueDate, int $tenantId)
    {
        $income = new Income();
        if(Income::whereId($id)->count() > 0){
            $income = Income::findOrFail($id);
        }
        $income->id = $id;
        $income->name = $name;
        $income->amount  = $amount;
        $income->isFixed = $isFixed;
        $income->due_date = $dueDate;
        $income->tenant_id = $tenantId;
        $income->save();
    }
}
