<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Car;
use App\Models\CarSupply;
use App\Models\Enum\SessionEnum;
use Carbon\Carbon;
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
        $this->setUser();
        $car = factory(Car::class)->create();
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

    public function testGet()
    {
        $this->setUser();
        factory(Car::class)->create();
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
        $this->setUser();
        $car = factory(Car::class)->create();
        $response = $this->get("car/$car->id/edit");

        $response
            ->assertStatus(200)
            ->assertViewIs('car.edit');
    }

    public function testUpdate()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $car = factory(Car::class)->create();
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
        $car = factory(Car::class)->create();
        $response = $this->delete("car/$car->id");

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/car")
            ->assertSessionHas('message', ['msg' => 'Carro deletado com sucesso', 'type' => SessionEnum::success]);

        $car = factory(Car::class)->create();
        factory(CarSupply::class)->create(['car_id' => $car->id]);
        $response = $this->delete("car/$car->id");

        $response
            ->assertStatus(302)
            ->assertRedirect("")
            ->assertSessionHas('message',
                [
                    'msg' => 'SQLSTATE[23000]: Integrity constraint violation: 1451 Cannot delete or update a parent row: a foreign key constraint fails (`personal_administrator_testing`.`car_supplies`, CONSTRAINT `car_supplies_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`)) (SQL: delete from `cars` where `id` = 2)' ,
                    'type' => SessionEnum::error
                ]
            );
    }
}
