<?php

/** @var Factory $factory */

use App\Models\Product;
use App\Models\ProductSupplier;
use App\Models\Supplier;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(ProductSupplier::class, function (/*Faker $faker*/) {
    return [
        'code' => Str::random(191),
        'un' => Str::random(191),
        'product_id' => factory(Product::class)->create()->id,
        'supplier_id' => factory(Supplier::class)->create()->id,
    ];
});
