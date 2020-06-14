<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TenantRoutesTrait;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait, TenantRoutesTrait;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $data = [
            'email' => 'michel.bolzon123@gmail.com',
            'password' => '1525605'
        ];
        $response = $this->post('login', $data);

        $subDomain = Tenant::first()->sub_domain;

        $response
            ->assertStatus(302)
            ->assertRedirect("$subDomain/home");
    }

    public function testLogout()
    {
        $subDomain = $this->setUser()->get('tenant')->sub_domain;

        $response = $this->post('logout');

        $response
            ->assertStatus(302)
            ->assertRedirect("$subDomain/home");
    }
}
