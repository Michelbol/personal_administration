<?php

/** @var Factory $factory */

use App\Models\Income;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Income::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'amount' => $faker->randomFloat(2, 0, 100000),
        'isFixed' => $faker->boolean,
        'due_date' => $faker->numberBetween(0, 31),
    ];
});
