<?php

/** @var Factory $factory */

use App\Models\Supplier;
use App\Models\Tenant;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Supplier::class, function (Faker $faker) {
    return [
        'company_name' => $faker->company,
        'fantasy_name' => $faker->company,
        'cnpj' => (string) $faker->numberBetween(10000000000000, 99999999999999),
        'address' => $faker->streetName,
        'address_number' => $faker->streetSuffix,
        'neighborhood' => $faker->secondaryAddress,
        'city' => $faker->city,
        'state' => $faker->state,
        'tenant_id' => Tenant::inRandomOrder()->first()->id
    ];
});
