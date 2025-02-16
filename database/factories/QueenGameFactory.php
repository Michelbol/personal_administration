<?php

namespace Database\Factories;

use App\QueenGame;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class QueenGameFactory extends Factory {

    public function definition()
    {
        return [
            'model' => fake()->text(),
            'white_left' => fake()->numberBetween(),
            'black_left' => fake()->numberBetween(),
            'difficulty' => fake()->numberBetween(),
            'start_game' => fake()->numberBetween(),
            'end_game' => fake()->numberBetween(),
            'type_white' => fake()->text(),
            'type_black' => fake()->text(),
            'type_black_machine' => fake()->text(),
            'type_white_machine' => fake()->text(),
        ];
    }
}
