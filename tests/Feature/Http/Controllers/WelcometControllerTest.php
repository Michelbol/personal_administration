<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TestCase;

class WelcometControllerTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $response = $this->get("$tenant->sub_domain/");

        $response
            ->assertStatus(200)
            ->assertViewIs('welcome');
    }
}
