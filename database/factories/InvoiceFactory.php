<?php

/** @var Factory $factory */

use App\Models\Invoice;
use App\Models\Supplier;
use App\Models\Tenant;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Invoice::class, function (Faker $faker) {
    return [
        'tenant_id' => Tenant::inRandomOrder()->first()->id,
        'supplier_id' => factory(Supplier::class)->create()->id,
        'number' => $faker->text(191),
        'series' => $faker->text(191),
        'emission_at' => $faker->date(),
        'authorization_protocol' => $faker->text(191),
        'authorization_at' => $faker->date(),
        'access_key' => $faker->text(191),
        'document' => $faker->text(191),
        'qr_code' => $faker->text(191),
        'taxes' => $faker->randomFloat(2),
        'discount' => $faker->randomFloat(2),
        'total_products' => $faker->randomFloat(2),
        'total_paid' => $faker->randomFloat(2),
    ];
});
