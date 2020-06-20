<?php

namespace Tests\Unit\App\Models;

use App\Models\Car;
use PHPUnit\Framework\TestCase;

class CarTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGets()
    {
        $car = new Car();
        $car->annual_insurance = null;
        $car->annual_licensing = null;

        $this->assertNull($car->annual_insurance);
        $this->assertNull($car->annual_licensing);
    }
}
