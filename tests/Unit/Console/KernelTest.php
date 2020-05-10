<?php

namespace Tests\Unit\Console;

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
            ->expectsOutput('No scheduled commands are ready to run.')
            ->assertExitCode(0);
    }
}
