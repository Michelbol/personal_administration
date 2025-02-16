<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{

    public function definition()
    {
        return [
            'company_name' => fake()->company,
            'fantasy_name' => fake()->company,
            'cnpj' => (string) fake()->numberBetween(10000000000000, 99999999999999),
            'address' => fake()->streetName,
            'address_number' => fake()->streetSuffix,
            'neighborhood' => fake()->secondaryAddress,
            'city' => fake()->city,
            'state' => fake()->state,
            'tenant_id' => Tenant::inRandomOrder()->first()->id
        ];
    }
}
