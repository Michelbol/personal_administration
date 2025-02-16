<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Enum\SessionEnum;
use App\Models\Income;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Str;
use Tests\SeedingTrait;
use Tests\TenantRoutesTrait;
use Tests\TestCase;

class IncomeControllerTest extends TestCase
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
        $response = $this->get('income');

        $response
            ->assertStatus(200)
            ->assertViewIs('income.index');
    }

    public function testCreate()
    {
        $this->setUser();
        $response = $this->get('income/create');

        $response
            ->assertStatus(200)
            ->assertViewIs('income.create');
    }

    public function testStore()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $income = Income::factory()->make()->toArray();
        $response = $this->post('income', $income);

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/income")
            ->assertSessionHas('message', ['msg' => 'Receita Salva com sucesso', 'type' => SessionEnum::success]);
    }

    public function testEdit()
    {
        $tenant = $this->setUser()->get('tenant');
        $income = Income::factory()->create(['tenant_id' => $tenant->id]);
        $response = $this->get("income/$income->id/edit");

        $response
            ->assertStatus(200)
            ->assertViewIs('income.edit');
    }


    public function testUpdate()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $income = Income::factory()->create(['tenant_id' => $tenant->id]);
        $data = $income->toArray();
        $data['name'] = Str::random();
        $response = $this->put("income/$income->id", $data);

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/income")
            ->assertSessionHas('message', ['msg' => 'Receita Atualizada com sucesso', 'type' => SessionEnum::success]);
    }

    public function testDestroy()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $income = Income::factory()->create(['tenant_id' => $tenant->id]);
        $response = $this->delete("income/$income->id");

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/income")
            ->assertSessionHas('message', ['msg' => 'Receita Excluida com sucesso', 'type' => SessionEnum::success]);
    }

    public function testGet()
    {
        $tenant = $this->setUser()->get('tenant');
        Income::factory()->create(['tenant_id' => $tenant->id]);
        $response = $this->get("income/get");

        $response
            ->assertStatus(200);
    }
}
