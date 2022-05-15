<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Enum\SessionEnum;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\ProductSupplier;
use App\Services\InvoiceService;
use Exception;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery;
use Tests\SeedingTrait;
use Tests\TenantRoutesTrait;
use Tests\TestCase;

class InvoiceProductControllerTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait, TenantRoutesTrait;

    public function testCreateByQrCode()
    {
        $this->setUser();
        $productSupplier = factory(ProductSupplier::class, 2)->create();

        $invoiceProduct = factory(InvoiceProduct::class)->create([
            'product_supplier_id' => $productSupplier->first()->id
        ]);

        $response = $this->put("invoice_product/$invoiceProduct->id", [
            'product_supplier_id' => $productSupplier->last()->id
        ]);

        $response
            ->assertStatus(200)
            ->json(['msg' => 'Produto Alterado com Sucesso']);
        $invoiceProduct->delete();
    }
}
