<?php

namespace Database\Factories;

use App\Models\UserTenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetFinancialFactory extends Factory
{

    public function definition()
    {
        $userTenant = UserTenant::inRandomOrder()->first();
        return [
            'month' => fake()->numberBetween(0,12),
            'year' => fake()->year,
            'isFinalized' => fake()->boolean,
            'initial_balance' => fake()->randomFloat(),
            'user_id' => $userTenant->id,
            'tenant_id' => $userTenant->tenant_id,
        ];
    }
}
