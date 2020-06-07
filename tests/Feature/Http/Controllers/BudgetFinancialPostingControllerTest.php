<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\BudgetFinancial;
use App\Models\BudgetFinancialPosting;
use App\Models\Enum\SessionEnum;
use App\Models\Expenses;
use App\Models\Income;
use App\Utilitarios;
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
        $subDomain = $this
            ->setUser()
            ->get('tenant')
            ->sub_domain;
        $budgetFinancial = factory(BudgetFinancial::class)->create();
        $budgetFinancialPosting = factory(BudgetFinancialPosting::class)
            ->make(['budget_financial_id' => $budgetFinancial->id])
            ->toArray();
        $budgetFinancialPosting['new_expense'] = factory(Expenses::class)
            ->make()
            ->toArray()['name'];
        $budgetFinancialPosting['new_income'] = factory(Income::class)
            ->make()
            ->toArray()['name'];
        $response = $this->post('budget_financial_posting', $budgetFinancialPosting);

        $response->assertStatus(302)
            ->assertRedirect("$subDomain/budget_financial/$budgetFinancial->id/edit")
            ->assertSessionHas('message', ['msg' => 'Lançamento Salvo com sucesso', 'type' => SessionEnum::success]);
    }

    public function testUpdate()
    {
        $subDomain = $this->setUser()->get('tenant')->sub_domain;

        $budgetFinancial = factory(BudgetFinancial::class)->create();
        $budgetFinancialPosting = factory(BudgetFinancialPosting::class)
            ->create(['budget_financial_id' => $budgetFinancial->id])
            ->toArray();
        $budgetFinancialPosting['posting_date'] = Carbon
            ::createFromFormat(
                'Y-m-d',
                $budgetFinancialPosting['posting_date']
            )
            ->format('d/m/Y');
        $budgetFinancialPosting['amount'] = 'R$: '.getFormatReal($budgetFinancialPosting['amount']);
        $budgetFinancialPosting['new_expense'] = factory(Expenses::class)
            ->make()
            ->toArray()['name'];
        $budgetFinancialPosting['new_income'] = factory(Income::class)
            ->make()
            ->toArray()['name'];

        $response = $this->put("budget_financial_posting/$budgetFinancialPosting[id]", $budgetFinancialPosting);

        $response->assertStatus(302)
            ->assertRedirect("$subDomain/budget_financial/$budgetFinancial->id/edit")
            ->assertSessionHas('message', ['msg' => 'Lançamento Atualizado com sucesso', 'type' => SessionEnum::success]);
    }

    public function testDestroy()
    {
        $subDomain = $this->setUser()->get('tenant')->sub_domain;

        $budgetFinancial = factory(BudgetFinancial::class)->create();
        $budgetFinancialPosting = factory(BudgetFinancialPosting::class)->create(['budget_financial_id' => $budgetFinancial->id]);

        $response = $this->delete("budget_financial_posting/$budgetFinancialPosting->id");

        $response->assertStatus(302)
            ->assertRedirect("$subDomain/budget_financial/$budgetFinancial->id/edit")
            ->assertSessionHas('message', ['msg' => 'Lançamento deletado com sucesso', 'type' => SessionEnum::success]);
    }

    public function testGet()
    {
        $this->setUser();

        $budgetFinancial = factory(BudgetFinancial::class)->create();
        $budgetFinancialPosting = factory(BudgetFinancialPosting::class)->create(['budget_financial_id' => $budgetFinancial->id]);

        $response = $this->get("budget_financial_posting/get/$budgetFinancial->id");

        $response->assertStatus(200);
    }
}
