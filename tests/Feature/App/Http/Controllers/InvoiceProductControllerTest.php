<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\ProductSupplier;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TenantRoutesTrait;
use Tests\TestCase;

class InvoiceProductControllerTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait, TenantRoutesTrait;

    public function testUpdate()
    {
        $this->setUser();
        $productSupplier = ProductSupplier::factory(2)->create();

        $invoiceProduct = InvoiceProduct::factory()->create([
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

    public function testCreateProductByInvoiceProduct()
    {
        $tenant = $this->setUser()->get('tenant');
        $supplier = Supplier::factory()->create([
            'tenant_id' => $tenant->id
        ]);
        $invoice = Invoice::factory()->create([
            'supplier_id' => $supplier->id,
            'tenant_id' => $tenant->id
        ]);
        $invoiceProduct = InvoiceProduct::factory()->create([
            'invoice_id' => $invoice->id
        ]);
        $response = $this->get("invoice_product/$invoiceProduct->id/create/product");

        $response
            ->assertRedirect("$tenant->sub_domain/invoice/$invoice->id/edit");
        $invoiceProduct->delete();
    }
}
