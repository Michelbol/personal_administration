<?php

namespace Database\Factories;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Factories\Factory;

class CreditCardFactory extends Factory {

    public function definition()
    {
        return [
            'name' => fake()->text(100),
            'limit' => fake()->randomFloat(),
            'default_closing_date' => fake()->randomNumber(),
            'bank_id' => Bank::inRandomOrder()->first(),
        ];
    }
}
