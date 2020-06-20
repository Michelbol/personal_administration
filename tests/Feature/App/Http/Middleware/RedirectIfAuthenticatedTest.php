<?php

namespace Tests\Feature\App\Http\Middleware;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TestCase;

class RedirectIfAuthenticatedTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHandle()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $response = $this->get("$tenant->sub_domain/login");

        $response
            ->assertStatus(302)
            ->assertRedirect('/home');
    }
}
