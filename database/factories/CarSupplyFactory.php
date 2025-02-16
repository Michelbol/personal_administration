<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Enum\FuelEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarSupplyFactory extends Factory
{

    public function definition()
    {
        $car = Car::inRandomOrder()->first();
        return [
            'date' => fake()->date(),
            'fuel' => fake()->randomElement(FuelEnum::getConstants()),
            'liters' => fake()->randomFloat(),
            'car_id' => $car->id,
            'kilometer' => fake()->randomFloat(),
            'total_paid' => fake()->randomFloat(),
            'gas_station' => fake()->text(150),
            'traveled_kilometers' => fake()->randomFloat(),
            'tenant_id' => $car->tenant_id,
        ];
    }
}
