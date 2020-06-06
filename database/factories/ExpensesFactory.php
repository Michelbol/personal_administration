<?php

/** @var Factory $factory */

use App\Models\Expenses;
use App\Models\Tenant;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Expenses::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'amount' => $faker->randomFloat(2, 0, 100000),
        'isFixed' => $faker->boolean,
        'due_date' => $faker->numberBetween(0, 31),
        'tenant_id' => Tenant::inRandomOrder()->first(),
    ];
});
