<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TenantRoutesTrait;
use Tests\TestCase;

class BrandControllerTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait, TenantRoutesTrait;


    public function testGet()
    {
        $this->setUser();
        factory(Brand::class)->create();
        $response = $this->get("brand/get");

        $response
            ->assertStatus(200);
    }
}
