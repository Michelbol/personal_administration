<?php

/** @var Factory $factory */

use App\Models\Car;
use App\Models\CarSupply;
use App\Models\Enum\FuelEnum;
use App\Models\Tenant;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(CarSupply::class, function (Faker $faker) {
    $car = Car::inRandomOrder()->first();
    return [
        'date' => $faker->date(),
        'fuel' => $faker->randomElement(FuelEnum::getConstants()),
        'liters' => $faker->randomFloat(),
        'car_id' => $car->id,
        'kilometer' => $faker->randomFloat(),
        'total_paid' => $faker->randomFloat(),
        'gas_station' => $faker->text(150),
        'traveled_kilometers' => $faker->randomFloat(),
        'tenant_id' => $car->tenant_id,
    ];
});
