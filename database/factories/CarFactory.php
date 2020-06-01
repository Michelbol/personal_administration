<?php

/** @var Factory $factory */

use App\Models\Car;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Car::class, function (Faker $faker) {
    return [
        'model' => $faker->randomAscii,
        'license_plate' => $faker->randomAscii,
        'annual_licensing' => $faker->dateTimeBetween('-1 year', 'now')->format('d/m/Y H:i'),
        'annual_insurance' => $faker->dateTimeBetween('-1 year', 'now')->format('d/m/Y H:i'),
        'tenant_id' => \App\Models\Tenant::inRandomOrder()->first(),
    ];
});
