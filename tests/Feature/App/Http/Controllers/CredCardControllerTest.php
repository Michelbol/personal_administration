<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\CreditCard;
use App\Models\Enum\SessionEnum;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Str;
use Tests\SeedingTrait;
use Tests\TenantRoutesTrait;
use Tests\TestCase;

class CredCardControllerTest extends TestCase
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
        $response = $this->get('cred_card');

        $response
            ->assertStatus(200)
            ->assertViewIs('cred_card.index');
    }

    public function testCreate()
    {
        $this->setUser();
        $response = $this->get('cred_card/create');

        $response
            ->assertStatus(200)
            ->assertViewIs('cred_card.create');
    }

    public function testStore()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $data = CreditCard::factory()->make()->toArray();
        $response = $this->post('cred_card', $data);

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/cred_card")
            ->assertSessionHas('message', ['msg' => 'Cartão de crédito salvo com sucesso', 'type' => SessionEnum::success]);
    }

    public function testEdit()
    {
        $this->setUser();
        $creditCard = CreditCard::factory()->create();
        $response = $this->get("cred_card/$creditCard->id/edit");

        $response
            ->assertStatus(200)
            ->assertViewIs('cred_card.edit');
    }

    public function testUpdate()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $creditCard = CreditCard::factory()->create();
        $data = $creditCard->toArray();
        $data['name'] = Str::random();
        $response = $this->put("cred_card/$creditCard->id", $data);

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/cred_card")
            ->assertSessionHas('message', ['msg' => 'Cartão Atualizado com sucesso', 'type' => SessionEnum::success]);
    }

    public function testDestroy()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $creditCard = CreditCard::factory()->create();
        $response = $this->delete("cred_card/$creditCard->id");

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/cred_card")
            ->assertSessionHas('message', ['msg' => 'Cartão de Crédito Deletado com sucesso', 'type' => SessionEnum::success]);
    }

    public function testGet()
    {
        $this->setUser();
        CreditCard::factory()->create();
        $response = $this->get('cred_card/get');

        $response->assertStatus(200);
    }
}
