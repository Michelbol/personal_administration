<?php

/** @var Factory $factory */

use App\Models\Car;
use App\Models\FipeHistory;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(FipeHistory::class, function (Faker $faker) {
    $car = Car::inRandomOrder()->first();
    return [
        'consultation_date' => $faker->dateTime(),
        'value' => $faker->numberBetween(1000, 10000),
        'car_id' => $car,
    ];
});
