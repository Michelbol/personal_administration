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
        $income = factory(Income::class)->make()->toArray();
        $response = $this->post('income', $income);

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/income")
            ->assertSessionHas('message', ['msg' => 'Receita Salva com sucesso', 'type' => SessionEnum::success]);
    }

    public function testEdit()
    {
        $this->setUser();
        $income = factory(Income::class)->create();
        $response = $this->get("income/$income->id/edit");

        $response
            ->assertStatus(200)
            ->assertViewIs('income.edit');
    }


    public function testUpdate()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $income = factory(Income::class)->create();
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
        $income = factory(Income::class)->create();
        $response = $this->delete("income/$income->id");

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/income")
            ->assertSessionHas('message', ['msg' => 'Receita Excluida com sucesso', 'type' => SessionEnum::success]);
    }

    public function testGet()
    {
        $this->setUser();
        factory(Income::class)->create();
        $response = $this->get("income/get");

        $response
            ->assertStatus(200);
    }
}
