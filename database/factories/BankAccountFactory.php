<?php

namespace Database\Factories;

/** @var Factory $factory */

use App\Models\Tenant;
use App\Models\BankAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankAccountFactory extends Factory {

    public function definition()
    {
        return [
            'name' => fake()->name,
            'agency' => fake()->numberBetween(0, 9999),
            'digit_agency' => fake()->numberBetween(0, 9),
            'number_account' => fake()->numberBetween(0,9999),
            'digit_account' => fake()->numberBetween(0, 9),
            'operation' => fake()->numberBetween(0, 100),
            'bank_id' => BankAccount::inRandomOrder()->first(),
            'tenant_id' => Tenant::inRandomOrder()->first(),
        ];
    }
}
