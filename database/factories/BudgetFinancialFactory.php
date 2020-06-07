<?php

/** @var Factory $factory */

use App\Models\BudgetFinancial;
use App\Models\Tenant;
use App\Models\UserTenant;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(BudgetFinancial::class, function (Faker $faker) {
    return [
        'month' => $faker->numberBetween(0,12),
        'year' => $faker->year,
        'isFinalized' => $faker->boolean,
        'initial_balance' => $faker->randomFloat(),
        'user_id' => UserTenant::inRandomOrder()->first(),
        'tenant_id' => Tenant::inRandomOrder()->first(),
    ];
});
