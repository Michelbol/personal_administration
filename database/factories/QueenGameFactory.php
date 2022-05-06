<?php

/** @var Factory $factory */


use App\QueenGame;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(QueenGame::class, function (Faker $faker) {
    return [
        'model' => $faker->text(),
        'white_left' => $faker->numberBetween(),
        'black_left' => $faker->numberBetween(),
        'difficulty' => $faker->numberBetween(),
        'start_game' => $faker->numberBetween(),
        'end_game' => $faker->numberBetween(),
        'type_white' => $faker->text(),
        'type_black' => $faker->text(),
        'type_black_machine' => $faker->text(),
        'type_white_machine' => $faker->text(),
    ];
});
