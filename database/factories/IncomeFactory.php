<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeFactory extends Factory {

    public function definition()
    {
        return [
            'name' => fake()->name,
            'amount' => fake()->randomFloat(2, 0, 100000),
            'isFixed' => fake()->boolean,
            'due_date' => fake()->numberBetween(0, 31),
            'tenant_id' => Tenant::inRandomOrder()->first(),
        ];
    }
}
