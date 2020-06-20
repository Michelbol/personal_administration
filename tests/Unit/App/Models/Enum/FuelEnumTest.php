<?php

namespace Tests\Unit\App\Models\Enum;

use App\Models\Enum\FuelEnum;
use Tests\TestCase;
use Str;

class FuelEnumTest extends TestCase
{

    public function testSetValue()
    {
        $this->assertEquals('Value not found', FuelEnum::getName(Str::random()));
        $this->assertEquals('Name not found', FuelEnum::getValue(Str::random()));

        $this->assertEquals(true, FuelEnum::isValidName('Diesel', true));
    }
}
