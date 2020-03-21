<?php

use App\Models\Expenses;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class ExpenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tenant = Tenant::first();
        $this->createExpense('Indefinida', 10, false, 18, null, $tenant->id);
        $this->createExpense('Tim', 1, true, 44.99, 7, $tenant->id);
        $this->createExpense('Boleto', 11, false, 38.50, null, $tenant->id);
        $this->createExpense('Cartão de Débito', 8, false, 3, null, $tenant->id);
        $this->createExpense('Saque', 13, false, 0, 31, $tenant->id);
        $this->createExpense('Seguradora', 12, true, 17.63, 15, $tenant->id);
    }
    public function createExpense(string $name, int $id, bool $isFixed, float $amount, $dueDate, int $tenantId)
    {
        $expense = new Expenses();
        if(Expenses::whereId($id)->count() > 0){
            $expense = Expenses::findOrFail($id);
        }
        $expense->id = $id;
        $expense->name = $name;
        $expense->amount  = $amount;
        $expense->isFixed = $isFixed;
        $expense->due_date = $dueDate;
        $expense->tenant_id = $tenantId;
        $expense->save();
    }
}
