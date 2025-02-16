<?php

namespace Tests\Unit\App\Console;

use Tests\TestCase;

class KernelTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSuccess()
    {
        $this->artisan('schedule:run')
            ->assertSuccessful();
    }
}
