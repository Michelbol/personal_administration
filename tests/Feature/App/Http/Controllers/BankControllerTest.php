<?php

namespace Tests\Feature\App\Http\Controllers;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TestCase;

class BankControllerTest extends TestCase
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
        $response = $this->get("$tenant->sub_domain/bank");

        $response
            ->assertStatus(200)
            ->assertJson([]);

        $response = $this->get("$tenant->sub_domain/bank?q=nu");

        $response
            ->assertStatus(200)
            ->assertJson([]);
    }
}
