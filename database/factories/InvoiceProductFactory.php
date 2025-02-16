<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\ProductSupplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceProductFactory extends Factory {

    public function definition()
    {
        return [
            'name' => fake()->name,
            'un' => fake()->text(5),
            'code' => (string) fake()->numberBetween(0, 999999999),
            'quantity' =>  fake()->randomFloat(2),
            'unitary_value' => fake()->randomFloat(2),
            'total_value' => fake()->randomFloat(2),
            'invoice_id' => Invoice::factory()->create()->id,
            'product_supplier_id' => ProductSupplier::factory()->create()->id,
        ];
    }
}
