<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Enum\SessionEnum;
use App\Models\Product;
use App\Models\ProductSupplier;
use App\Models\Supplier;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TenantRoutesTrait;
use Tests\TestCase;

class ProductSupplierControllerTest extends TestCase
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
        $product = Product::factory()->create(['tenant_id' => $tenant->id]);
        Supplier::factory()->create(['tenant_id' => $tenant->id]);
        ProductSupplier::factory()->create();
        $response = $this->get("product_supplier/get/$product->id");

        $response->assertStatus(200);
    }

    public function testShow()
    {
        $tenant = $this->setUser()->get('tenant');
        Product::factory()->create(['tenant_id' => $tenant->id]);
        Supplier::factory()->create(['tenant_id' => $tenant->id]);
        $productSupplier = ProductSupplier::factory()->create();
        $response = $this->get("product_supplier/$productSupplier->id");

        $response->assertStatus(200);
    }
    public function testUpdate()
    {
        /**
         * @var ProductSupplier $productSupplier
         * @var Tenant $tenant
         */
        $tenant = $this->setUser()->get('tenant');
        Product::factory()->create(['tenant_id' => $tenant->id]);
        Supplier::factory()->create(['tenant_id' => $tenant->id]);

        $productSupplier = ProductSupplier::factory()->create();
        $data = ProductSupplier::factory()->make()->toArray();
        unset($data['id']);
        $response = $this->put("product_supplier/$productSupplier->id", $data);

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/product")
            ->assertSessionHas('message', ['msg'=>'Fornecedor atualizado com sucesso', 'type' => SessionEnum::success]);

        unset($data['code']);
        $response = $this->put("product_supplier/$productSupplier->id", $data);

        $response
            ->assertStatus(302)
            ->assertRedirect("")
            ->assertSessionHas('message', ['msg'=>'O campo code é obrigatório.', 'type' => SessionEnum::error]);

    }

}
