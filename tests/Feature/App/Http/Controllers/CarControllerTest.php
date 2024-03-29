<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Car;
use App\Models\CarSupply;
use App\Models\Enum\SessionEnum;
use App\Services\FipeService;
use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TenantRoutesTrait;
use Tests\TestCase;

class CarControllerTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait, TenantRoutesTrait;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testProfile()
    {
        $tenant = $this->setUser()->get('tenant');
        $car = factory(Car::class)->create(['tenant_id' => $tenant->id]);
        factory(CarSupply::class)
            ->create
            (
                [
                    'date' => now(),
                    'car_id' => $car->id
                ]
            );
        $response = $this->get("car/profile/$car->id");

        $response
            ->assertStatus(200)
            ->assertViewIs('car.profile');
    }

    public function testCreate()
    {
        $this->setUser();
        $response = $this->get('car/create');
        $response
            ->assertStatus(200)
            ->assertViewIs('car.create');
    }

    public function testProfileWithPeriod()
    {
        $tenant = $this->setUser()->get('tenant');
        $car = factory(Car::class)->create(['tenant_id' => $tenant->id]);
        factory(CarSupply::class)
            ->create
            (
                [
                    'date' => now(),
                    'car_id' => $car->id
                ]
            );
        $period = now()->format('d/m/Y').' - '.now()->addDay()->format('d/m/Y');
        $response = $this->get("car/profile/$car->id?period=$period");

        $response
            ->assertStatus(200)
            ->assertViewIs('car.profile');
    }

    public function testGet()
    {
        $tenant = $this->setUser()->get('tenant');
        factory(Car::class)->create(['tenant_id' => $tenant->id]);
        $response = $this->get("car/get");

        $response
            ->assertStatus(200);
    }

    public function testStore()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $car = factory(Car::class)->make();
        $response = $this->post("car/", $car->toArray());

        $response
            ->assertStatus(302)
        ->assertRedirect("$tenant->sub_domain/car")
        ->assertSessionHas('message', ['msg' => 'Carro incluido com sucesso', 'type' => SessionEnum::success]);

        $car = factory(Car::class)->make()->toArray();
        unset($car['license_plate']);
        $response = $this->post("car/", $car);

        $response
            ->assertStatus(302)
            ->assertRedirect("")
            ->assertSessionHas('message', ['msg' => 'The given data was invalid.', 'type' => SessionEnum::error]);
    }

    public function testEdit()
    {
        $tenant = $this->setUser()->get('tenant');
        $car = factory(Car::class)->create(['tenant_id' => $tenant->id]);
        $response = $this->get("car/$car->id/edit");

        $response
            ->assertStatus(200)
            ->assertViewIs('car.edit');
    }

    public function testEditException()
    {
        $instance = \Mockery
            ::mock(FipeService::class)
            ->shouldReceive('getBrands')
            ->andThrow(Exception::class)
            ->getMock();

        $this->instance(FipeService::class, $instance);

        $tenant = $this->setUser()->get('tenant');
        $car = factory(Car::class)->create(['tenant_id' => $tenant->id]);
        $response = $this->get("car/$car->id/edit");

        $response
            ->assertStatus(200)
            ->assertViewIs('car.edit');
    }

    public function testUpdate()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $car = factory(Car::class)->create(['tenant_id' => $tenant->id]);
        $data = factory(Car::class)->make()->toArray();
        $response = $this->put("car/$car->id", $data);

        $response
            ->assertStatus(302)
            ->assertSessionHas('message', ['msg' => 'Carro atualizado com sucesso', 'type' => SessionEnum::success])
            ->assertRedirect("$tenant->sub_domain/car");

        $data = factory(Car::class)->make()->toArray();
        unset($data['license_plate']);
        $response = $this->put("car/$car->id", $data);

        $response
            ->assertStatus(302)
            ->assertRedirect("")
            ->assertSessionHas('message', ['msg' => 'The given data was invalid.', 'type' => SessionEnum::error]);
    }

    public function testDestroy()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $car = factory(Car::class)->create(['tenant_id' => $tenant->id]);
        $response = $this->delete("car/$car->id");

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/car")
            ->assertSessionHas('message', ['msg' => 'Carro deletado com sucesso', 'type' => SessionEnum::success]);

        $car = factory(Car::class)->create(['tenant_id' => $tenant->id]);
        factory(CarSupply::class)->create(['car_id' => $car->id, 'tenant_id' => $tenant->id]);
        $response = $this->delete("car/$car->id");
        $databaseName = config('database.connections')[config('database.default')]['database'];
        $response
            ->assertStatus(302)
            ->assertRedirect("")
            ->assertSessionHas('message',
                [
                    'msg' => "SQLSTATE[23000]: Integrity constraint violation: 1451 Cannot delete or update a parent row: a foreign key constraint fails (`$databaseName`.`car_supplies`, CONSTRAINT `car_supplies_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`)) (SQL: delete from `cars` where `id` = 2)" ,
                    'type' => SessionEnum::error
                ]
            );
    }
}
