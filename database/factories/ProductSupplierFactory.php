<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductSupplierFactory extends Factory {

    public function definition()
    {
        return [
            'code' => Str::random(191),
            'un' => Str::random(191),
            'product_id' => Product::factory()->create()->id,
            'supplier_id' => Supplier::factory()->create()->id,
        ];
    }
}
