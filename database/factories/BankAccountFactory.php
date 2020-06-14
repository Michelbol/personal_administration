<?php

/** @var Factory $factory */

use App\Models\Tenant;
use Faker\Generator as Faker;
use App\Models\BankAccount;
use Illuminate\Database\Eloquent\Factory;

$factory->define(BankAccount::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'agency' => $faker->numberBetween(0, 9999),
        'digit_agency' => $faker->numberBetween(0, 9),
        'number_account' => $faker->numberBetween(0,9999),
        'digit_account' => $faker->numberBetween(0, 9),
        'operation' => $faker->numberBetween(0, 100),
        'bank_id' => BankAccount::inRandomOrder()->first(),
        'tenant_id' => Tenant::inRandomOrder()->first(),
    ];
});
