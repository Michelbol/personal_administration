<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase {

    public static function possibleNumberToBeCleanedAndExpectedResult() {
        yield ['123456', '123456'];
        yield ['12a3456', '123456'];
        yield ['b12a3456', '123456'];
        yield ['-b12a3456', '123456'];
        yield ['111.111.111-11', '11111111111'];
        yield ['11.111.111/0001-11', '11111111000111'];
        yield [null, null];
        yield ['', ''];
    }

    /** @dataProvider possibleNumberToBeCleanedAndExpectedResult */
    public function testCleanNumber(?string $number, ?string $expectedNumber) {
        $numberClean = cleanNumber($number);

        $this->assertEquals($expectedNumber, $numberClean);
    }
}
