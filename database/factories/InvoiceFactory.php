<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory {

    public function definition()
    {
        $supplier = Supplier::factory()->create();
        return [
            'tenant_id' => $supplier->tenant_id,
            'supplier_id' => $supplier->id,
            'number' => fake()->text(191),
            'series' => fake()->text(191),
            'emission_at' => fake()->date(),
            'authorization_protocol' => fake()->text(191),
            'authorization_at' => fake()->date(),
            'access_key' => fake()->text(191),
            'document' => fake()->text(191),
            'qr_code' => fake()->text(191),
            'taxes' => fake()->randomFloat(2),
            'discount' => fake()->randomFloat(2),
            'total_products' => fake()->randomFloat(2),
            'total_paid' => fake()->randomFloat(2),
        ];
    }
}
