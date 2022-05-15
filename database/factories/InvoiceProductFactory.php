<?php

/** @var Factory $factory */

use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\ProductSupplier;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(InvoiceProduct::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'un' => $faker->text(5),
        'code' => (string) $faker->numberBetween(0, 999999999),
        'quantity' =>  $faker->randomFloat(2),
        'unitary_value' => $faker->randomFloat(2),
        'total_value' => $faker->randomFloat(2),
        'invoice_id' => factory(Invoice::class)->create()->id,
        'product_supplier_id' => factory(ProductSupplier::class)->create()->id,
    ];
});
