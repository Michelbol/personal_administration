<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TenantRoutesTrait;
use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait, TenantRoutesTrait;

    public function testCreateByQrCode()
    {
        $this->setUser();
        $response = $this->get('invoice/create/qr_code');

        $response
            ->assertStatus(200)
            ->assertViewIs('invoice.create.qr_code');
    }

    public function testGet()
    {
        $tenant = $this->setUser()->get('tenant');
        factory(Invoice::class)->create(['tenant_id' => $tenant->id]);
        $response = $this->get("invoice/get");

        $response
            ->assertStatus(200);
    }

    public function testEdit()
    {
        $tenant = $this->setUser()->get('tenant');
        $invoice = factory(Invoice::class)->create(['tenant_id' => $tenant->id]);
        $response = $this->get("invoice/$invoice->id/edit");

        $response
            ->assertStatus(200)
            ->assertViewIs('invoice.edit');
    }

    public function testShow()
    {
        $tenant = $this->setUser()->get('tenant');
        $invoice = factory(Invoice::class)->create(['tenant_id' => $tenant->id]);
        $response = $this->get("invoice/$invoice->id");

        $response
            ->assertStatus(200)
            ->assertViewIs('invoice.show');
    }
}
