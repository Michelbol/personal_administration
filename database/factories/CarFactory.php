<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory {

    public function definition()
    {
        return [
            'model' => fake()->randomAscii,
            'brand' => fake()->randomAscii,
            'year' => fake()->numberBetween(2000, 2100),
            'license_plate' => fake()->randomAscii,
            'annual_licensing' => fake()->dateTimeBetween('-1 year', 'now')->format('d/m/Y H:i'),
            'annual_insurance' => fake()->dateTimeBetween('-1 year', 'now')->format('d/m/Y H:i'),
            'tenant_id' => Tenant::inRandomOrder()->first(),
        ];
    }
}
