<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\BudgetFinancial;
use App\Models\BudgetFinancialPosting;
use App\Models\Enum\SessionEnum;
use App\Models\Expenses;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TenantRoutesTrait;
use Tests\TestCase;

class BudgetFinancialPostingControllerTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait, TenantRoutesTrait;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStore()
    {
        $tenant = $this
            ->setUser()
            ->get('tenant');
        $subDomain = $tenant->sub_domain;
        $budgetFinancial = BudgetFinancial::factory()->create(['tenant_id' => $tenant->id]);
        $budgetFinancialPosting = BudgetFinancialPosting::factory()
            ->make(['budget_financial_id' => $budgetFinancial->id])
            ->toArray();
        $budgetFinancialPosting['new_expense'] = Expenses::factory()
            ->make()
            ->toArray()['name'];
        $budgetFinancialPosting['new_income'] = Income::factory()
            ->make()
            ->toArray()['name'];
        $response = $this->post('budget_financial_posting', $budgetFinancialPosting);


        $response->assertStatus(302)
            ->assertRedirect("$subDomain/budget_financial/$budgetFinancial->id/edit")
            ->assertSessionHas('message', ['msg' => 'Lançamento Salvo com sucesso', 'type' => SessionEnum::success]);
    }

    public function testUpdate()
    {
        $tenant = $this
            ->setUser()
            ->get('tenant');
        $subDomain = $tenant->sub_domain;
        $budgetFinancial = BudgetFinancial::factory()->create(['tenant_id' => $tenant->id]);
        $budgetFinancialPosting = BudgetFinancialPosting::factory()
            ->create(['budget_financial_id' => $budgetFinancial->id])
            ->toArray();
        $budgetFinancialPosting['posting_date'] = Carbon
            ::createFromFormat(
                'Y-m-d',
                $budgetFinancialPosting['posting_date']
            )
            ->format('d/m/Y');
        $budgetFinancialPosting['amount'] = 'R$: '.getFormatReal($budgetFinancialPosting['amount']);
        $budgetFinancialPosting['new_expense'] = Expenses::factory()
            ->make()
            ->toArray()['name'];
        $budgetFinancialPosting['new_income'] = Income::factory()
            ->make()
            ->toArray()['name'];

        $response = $this->put("budget_financial_posting/$budgetFinancialPosting[id]", $budgetFinancialPosting);

        $response->assertStatus(302)
            ->assertRedirect("$subDomain/budget_financial/$budgetFinancial->id/edit")
            ->assertSessionHas('message', ['msg' => 'Lançamento Atualizado com sucesso', 'type' => SessionEnum::success]);
    }

    public function testDestroy()
    {
        $tenant = $this
            ->setUser()
            ->get('tenant');
        $subDomain = $tenant->sub_domain;
        $budgetFinancial = BudgetFinancial::factory()->create(['tenant_id' => $tenant->id]);
        $budgetFinancialPosting = BudgetFinancialPosting::factory()->create(['budget_financial_id' => $budgetFinancial->id]);

        $response = $this->delete("budget_financial_posting/$budgetFinancialPosting->id");

        $response->assertStatus(302)
            ->assertRedirect("$subDomain/budget_financial/$budgetFinancial->id/edit")
            ->assertSessionHas('message', ['msg' => 'Lançamento deletado com sucesso', 'type' => SessionEnum::success]);
    }

    public function testGet()
    {
        $this->setUser();

        $budgetFinancial = BudgetFinancial::factory()->create();
        BudgetFinancialPosting::factory()->create(['budget_financial_id' => $budgetFinancial->id]);

        $response = $this->get("budget_financial_posting/get/$budgetFinancial->id");

        $response->assertStatus(200);
    }
}
