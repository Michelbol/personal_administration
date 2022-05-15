<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\Enum\SessionEnum;
use App\Models\Invoice;
use App\Models\Supplier;
use App\Services\InvoiceService;
use Exception;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery;
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
        $supplier = factory(Supplier::class)->create([
            'tenant_id' => $tenant->id
        ]);
        $invoice = factory(Invoice::class)->create([
            'tenant_id' => $supplier->tenant_id,
            'supplier_id' => $supplier->id
        ]);
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

    public function testStoreByQrCode()
    {
        $tenant = $this->setUser()->get('tenant');

        $invoice = factory(Invoice::class)->create([
            'tenant_id' => $tenant->id,
        ]);

        $instanceInvoiceService = Mockery
            ::mock(InvoiceService::class)
            ->makePartial()
            ->shouldReceive('createInvoiceByQrCode')
            ->once()
            ->andReturn($invoice)
            ->getMock();

        $this->instance(InvoiceService::class, $instanceInvoiceService);

        $faker = Factory::create();

        $response = $this->post("invoice/qr_code", [
            'url_qr_code' => $faker->url
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/invoice/$invoice->id/edit")
            ->assertSessionHas('message', ['msg' => 'Nota salva com sucesso', 'type' => SessionEnum::success]);
    }

    public function testStoreByQrCodeAndExpectsError()
    {
        $tenant = $this->setUser()->get('tenant');

        $msgError = 'Mensagem de erro';

        $instanceInvoiceService = Mockery
            ::mock(InvoiceService::class)
            ->makePartial()
            ->shouldReceive('createInvoiceByQrCode')
            ->once()
            ->andThrow(new Exception($msgError))
            ->getMock();

        $this->instance(InvoiceService::class, $instanceInvoiceService);

        $faker = Factory::create();

        $response = $this->post("invoice/qr_code", [
            'url_qr_code' => $faker->url
        ]);

        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/invoice/create/qr_code")
            ->assertSessionHas('message', ['msg' => $msgError, 'type' => SessionEnum::error]);
    }
}
