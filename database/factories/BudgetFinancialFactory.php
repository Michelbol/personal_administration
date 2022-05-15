<?php

/** @var Factory $factory */

use App\Models\BudgetFinancial;
use App\Models\Tenant;
use App\Models\UserTenant;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(BudgetFinancial::class, function (Faker $faker) {
    $userTenant = UserTenant::inRandomOrder()->first();
    return [
        'month' => $faker->numberBetween(0,12),
        'year' => $faker->year,
        'isFinalized' => $faker->boolean,
        'initial_balance' => $faker->randomFloat(),
        'user_id' => $userTenant->id,
        'tenant_id' => $userTenant->tenant_id,
    ];
});
