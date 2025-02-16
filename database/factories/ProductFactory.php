<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory {

    public function definition()
    {
        return [
            'name' => fake()->name,
            'tenant_id' => Tenant::inRandomOrder()->first()->id
        ];
    }
}
