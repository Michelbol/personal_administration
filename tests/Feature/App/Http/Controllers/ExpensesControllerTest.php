<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Enum\SessionEnum;
use App\Models\Expenses;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TenantRoutesTrait;
use Tests\TestCase;
use Str;

class ExpensesControllerTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait, TenantRoutesTrait;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $this->setUser();
        $response = $this->get('expense');

        $response
            ->assertStatus(200)
            ->assertViewIs('expense.index');
    }

    public function testCreate()
    {
        $this->setUser();
        $response = $this->get('expense/create');

        $response
            ->assertStatus(200)
            ->assertViewIs('expense.create');
    }

    public function testStore()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $expense = Expenses::factory()->make()->toArray();
        $response = $this->post('expense', $expense);

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/expense")
            ->assertSessionHas('message', ['msg' => 'Despesa Salva com sucesso', 'type' => SessionEnum::success]);
    }

    public function testEdit()
    {
        $tenant = $this->setUser()->get('tenant');
        $expense = Expenses::factory()->create(['tenant_id' => $tenant->id]);
        $response = $this->get("expense/$expense->id/edit");

        $response
            ->assertStatus(200)
            ->assertViewIs('expense.edit');
    }


    public function testUpdate()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $expense = Expenses::factory()->create(['tenant_id' => $tenant->id]);
        $data = $expense->toArray();
        $data['name'] = Str::random();
        $response = $this->put("expense/$expense->id", $data);

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/expense")
            ->assertSessionHas('message', ['msg' => 'Despesas Atualizada com sucesso', 'type' => SessionEnum::success]);
    }

    public function testDestroy()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $expense = Expenses::factory()->create(['tenant_id' => $tenant->id]);
        $response = $this->delete("expense/$expense->id");

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/expense")
            ->assertSessionHas('message', ['msg' => 'Despesa Excluida com sucesso', 'type' => SessionEnum::success]);
    }

    public function testGet()
    {
        $tenant = $this->setUser()->get('tenant');
        Expenses::factory()->create(['tenant_id' => $tenant->id]);
        $response = $this->get("expense/get");

        $response
            ->assertStatus(200);
    }
}
