<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TenantRoutesTrait;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait, TenantRoutesTrait;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGet()
    {
        $tenant = $this->setUser()->get('tenant');
        Product::factory()->create(['tenant_id' => $tenant->id]);
        $response = $this->get('product/get');

        $response->assertStatus(200);
    }

    public function testEdit()
    {
        $tenant = $this->setUser()->get('tenant');
        $product = Product::factory()->create(['tenant_id' => $tenant->id]);
        $response = $this->get("product/$product->id/edit");

        $response
            ->assertStatus(200)
            ->assertViewIs('product.edit');
    }
}
