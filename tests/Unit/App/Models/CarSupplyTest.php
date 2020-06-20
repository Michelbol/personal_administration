<?php

namespace Tests\Unit\App\Models;

use App\Models\CarSupply;
use PHPUnit\Framework\TestCase;

class CarSupplyTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGets()
    {
        $carSupply = new CarSupply();

        $this->assertIsString($carSupply->kilometer);
        $this->assertIsString($carSupply->liters);
        $this->assertIsString($carSupply->total_paid);
        $this->assertIsString($carSupply->traveled_kilometers);
        $this->assertNull($carSupply->date);
    }
}
