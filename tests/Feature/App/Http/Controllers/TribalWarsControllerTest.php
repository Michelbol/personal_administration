<?php

namespace Tests\Feature\App\Http\Controllers;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TestCase;

class TribalWarsControllerTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait;

    public function testIndex()
    {
        $response = $this->get("tribal-wars");

        $response
            ->assertStatus(200)
            ->assertViewIs('tribal.index');
    }

    public function testCollect()
    {
        $response = $this->get("tribal-wars/collect");

        $response
            ->assertStatus(200)
            ->assertViewIs('tribal.collect');
    }
}
