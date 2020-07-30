<?php

/** @var Factory $factory */

use App\Models\Product;
use App\Models\Tenant;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'tenant_id' => Tenant::inRandomOrder()->first()->id
    ];
});
