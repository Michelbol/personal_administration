<?php
/** @var Factory $factory */

use App\Http\Requests\ContactFormCurriculumRequest;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(ContactFormCurriculumRequest::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'message' => $faker->text,
    ];
});
