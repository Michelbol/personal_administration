<?php

namespace Tests\Feature\App\Http\Middleware;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TestCase;

class AuthenticateTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHandle()
    {
        $tenant = Tenant::first();
        $response = $this->get("$tenant->sub_domain/bank");

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/login");
    }
}
