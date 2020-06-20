<?php

namespace Tests\Feature\App\Models;

use App\Models\Car;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TestCase;

class CarTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testcarSupplies()
    {
        $car = factory(Car::class)->create();
        $this->assertIsIterable($car->carSupplies);
    }
}
