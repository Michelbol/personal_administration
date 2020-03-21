<?php

namespace Tests\Feature;

use App\Models\BankAccount;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\SeedingTrait;
use Tests\TestCase;

class BankAccountTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $response = $this->get("$tenant->sub_domain/bank_accounts");

        $response->assertStatus(200)
            ->assertViewIs('bank_account.index');
    }

    public function testGet()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $response = $this->get("$tenant->sub_domain/bank_account/get");

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function testCreate()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $response = $this->get("$tenant->sub_domain/bank_accounts/create");

        $response->assertStatus(200)
            ->assertViewIs('bank_account.create');
    }

    public function testEdit()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $bankAccount = BankAccount::first();
        $response = $this->get("$tenant->sub_domain/bank_accounts/$bankAccount->id/edit");

        $response->assertStatus(200)
            ->assertViewIs('bank_account.edit');
    }
}
