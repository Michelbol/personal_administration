<?php

/** @var Factory $factory */

use App\Models\Bank;
use App\Models\CreditCard;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(CreditCard::class, function (Faker $faker) {
    return [
        'name' => $faker->text(100),
        'limit' => $faker->randomFloat(),
        'default_closing_date' => $faker->randomNumber(),
        'bank_id' => Bank::inRandomOrder()->first(),
    ];
});
