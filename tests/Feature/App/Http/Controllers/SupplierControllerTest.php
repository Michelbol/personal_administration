<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TenantRoutesTrait;
use Tests\TestCase;

class SupplierControllerTest extends TestCase
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
        Supplier::factory()->create(['tenant_id' => $tenant->id]);
        $response = $this->get('supplier/get');

        $response->assertStatus(200);
    }
}
