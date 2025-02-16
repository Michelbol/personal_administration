<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankAccountPosting;
use App\Models\BudgetFinancial;
use App\Models\Enum\SessionEnum;
use App\Models\Expenses;
use App\Models\Income;
use Carbon\Carbon;
use DB;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TenantRoutesTrait;
use Tests\TestCase;

class BudgetFinancialControllerTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait, TenantRoutesTrait;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $object = $this->setUser();
        $user = $object->get('user');
        $response = $this->get('budget_financial');

        $response
            ->assertStatus(200)
            ->assertViewIs('budget_financial.index');

        $response = $this->get("budget_financial?user_id=$user->id");

        $response
            ->assertStatus(200)
            ->assertViewIs('budget_financial.index');

        $response = $this->get("budget_financial?year=".now()->year);

        $response
            ->assertStatus(200)
            ->assertViewIs('budget_financial.index');
    }

    public function testIndexWithoutExpensesAndIncomes()
    {
        $this->setUser();
        DB::delete('delete from key_file_type_bank_account_postings');
        DB::delete('delete from expenses');
        DB::delete('delete from incomes');

        $response = $this->get('budget_financial');

        $response
            ->assertStatus(200)
            ->assertViewIs('budget_financial.index');
    }

    public function testEdit()
    {
        $this->setUser();

        $response = $this->get('budget_financial');

        $response
            ->assertStatus(200)
            ->assertViewIs('budget_financial.index');

        $budgetFinancial = BudgetFinancial::first();
        $response = $this->get("budget_financial/$budgetFinancial->id/edit");
        $response
            ->assertStatus(200)
            ->assertViewIs('budget_financial.edit');
    }

    public function testUpdateInitialBalance()
    {
        $subDomain = $this->setUser()->get('tenant')->sub_domain;

        $response = $this->get('budget_financial');

        $response
            ->assertStatus(200)
            ->assertViewIs('budget_financial.index');

        $budgetFinancial = BudgetFinancial::first();
        $response = $this
            ->post(
                "budget_financial/updateinitialbalance/$budgetFinancial->id",
                ['initial_balance' => rand(0,100000)]
            );
        $response
            ->assertStatus(302)
            ->assertRedirect("$subDomain/budget_financial/$budgetFinancial->id/edit");
    }

    public function testLastMonth()
    {
        $subDomain = $this->setUser()->get('tenant')->sub_domain;

        $response = $this->get('budget_financial');

        $response
            ->assertStatus(200)
            ->assertViewIs('budget_financial.index');

        $budgetFinancial = BudgetFinancial::where('month', 2)->first();
        $response = $this
            ->get("budget_financial/last_month/$budgetFinancial->id");

        $response
            ->assertStatus(302)
            ->assertRedirect("$subDomain/budget_financial/$budgetFinancial->id/edit");
        $budgetFinancial = BudgetFinancial::where('month', 1)->first();
        $response = $this
            ->get("budget_financial/last_month/$budgetFinancial->id");
        $response
            ->assertStatus(302)
            ->assertRedirect("$subDomain/budget_financial/$budgetFinancial->id/edit");
    }


    public function testBudgetFinancialMonthByBankAccount()
    {
        $subDomain = $this->setUser()->get('tenant')->sub_domain;

        $response = $this->get('budget_financial');

        $response
            ->assertStatus(200)
            ->assertViewIs('budget_financial.index');

        $budgetFinancial = BudgetFinancial::first();

        $bank = BankAccount::factory()->create();
        BankAccountPosting::factory()->create([
            'bank_account_id' => $bank->id,
            'posting_date' => Carbon::create($budgetFinancial->year, $budgetFinancial->month)->format('d/m/Y H:i')
        ]);
        $response = $this
            ->get("budget_financial/restart/$budgetFinancial->id");
        $response
            ->assertStatus(302)
            ->assertRedirect("$subDomain/budget_financial/$budgetFinancial->id/edit");
    }

    public function testGenerateFixed()
    {
        $subDomain = $this->setUser()->get('tenant')->sub_domain;

        $response = $this->get('budget_financial');

        $response
            ->assertStatus(200)
            ->assertViewIs('budget_financial.index');

        $budgetFinancial = BudgetFinancial::first();

        $response2 = $this->get("budget_financial/$budgetFinancial->id/edit");

        $response2
            ->assertStatus(200)
            ->assertViewIs('budget_financial.edit');

        Income::factory(5)->create(['isFixed' => true]);
        Expenses::factory(5)->create(['isFixed' => true]);

        $response3 = $this
            ->get("budget_financial/generate_fixed/$budgetFinancial->id");

        $response3
            ->assertRedirect("$subDomain/budget_financial/$budgetFinancial->id/edit")
            ->assertSessionHas('message', ['msg' => 'Orçamento reiniciado com sucesso!', 'type' => SessionEnum::success]);
    }
}
