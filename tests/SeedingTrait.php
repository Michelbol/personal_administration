<?php

namespace Tests;

trait SeedingTrait
{

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }
}
