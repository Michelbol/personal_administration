<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Car;
use App\Models\CarSupply;
use App\Models\Enum\SessionEnum;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Str;
use Tests\SeedingTrait;
use Tests\TenantRoutesTrait;
use Tests\TestCase;

class CarSupplyControllerTest extends TestCase
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
        $car = factory(Car::class)->create();
        $response = $this->get("car_supply/$car->id");

        $response
            ->assertStatus(200)
            ->assertViewIs('car.supply.index');
    }

    public function testCreate()
    {
        $this->setUser();
        $car = factory(Car::class)->create();
        $response = $this->get("car_supply/create/$car->id");

        $response
            ->assertStatus(200)
            ->assertViewIs('car.supply.create');
    }

    public function testStore()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        factory(Car::class)->create();
        $carSupply = factory(CarSupply::class)->make();
        $response = $this->post("car_supply", $carSupply->toArray());

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/car_supply/$carSupply->car_id")
            ->assertSessionHas('message', ['msg' => 'Abastecimento incluido com sucesso', 'type' => SessionEnum::success]);
    }

    public function testEdit()
    {
        $this->setUser();
        factory(Car::class)->create();
        $carSupply = factory(CarSupply::class)->create();
        $response = $this->get("car_supply/$carSupply->id/edit");

        $response
            ->assertStatus(200)
            ->assertViewIs('car.supply.edit');
    }

    public function testUpdate()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        factory(Car::class)->create();
        $carSupply = factory(CarSupply::class)->create();
        $data = $carSupply->toArray();
        $data['gas_station'] = Str::random();
        $response = $this->put("car_supply/$carSupply->id", $data);

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/car_supply/$carSupply->car_id")
        ->assertSessionHas('message', ['msg' => 'Abastecimento Atualizado com sucesso', 'type' => SessionEnum::success]);
    }

    public function testDestroy()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        factory(Car::class)->create();
        $carSupply = factory(CarSupply::class)->create();
        $response = $this->delete("car_supply/$carSupply->id");

        $response->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/car_supply/$carSupply->car_id")
            ->assertSessionHas('message', ['msg' => 'Abastecimento deletado com sucesso', 'type' => SessionEnum::success]);
    }

    public function testGet()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        factory(Car::class)->create();
        $carSupply = factory(CarSupply::class)->create();
        $response = $this->get("car_supply/get/$carSupply->id");

        $response->assertStatus(200);
    }
}
