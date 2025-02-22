<?php

namespace Tests\Feature;

use Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TestCase;

class DashboardTest extends TestCase
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
        $response = $this->get("$tenant->sub_domain/home");

        $response->assertStatus(200)
        ->assertViewIs('home');

        $response = $this->get("$tenant->sub_domain/home?year=2019");
        $response->assertStatus(200)
            ->assertViewIs('home');
    }
}
