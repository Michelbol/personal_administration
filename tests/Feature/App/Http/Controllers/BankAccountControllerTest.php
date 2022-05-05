<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankAccountPosting;
use App\Models\Expenses;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TestCase;

class BankAccountControllerTest extends TestCase
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

    public function testEditWithPeriod()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $bankAccount = BankAccount::first();
        $period = now()->format('d/m/Y').' - '.now()->addDay()->format('d/m/Y');
        $response = $this->get("$tenant->sub_domain/bank_accounts/$bankAccount->id/edit?period_date=$period");

        $response->assertStatus(200)
            ->assertViewIs('bank_account.edit');
    }

    public function testReportExpense()
    {
        $tenant = $this->setUser()->get('tenant');
        $response = $this->get("$tenant->sub_domain/bank_account/expense/report");

        $response
            ->assertStatus(200)
            ->assertViewIs('bank_account.report_expense');
    }

    public function testReportExpenseWithYear()
    {
        $tenant = $this->setUser()->get('tenant');
        $year = now()->year;
        $response = $this->get("$tenant->sub_domain/bank_account/expense/report?year=$year");

        $response
            ->assertStatus(200)
            ->assertViewIs('bank_account.report_expense');
    }

    public function testReportExpenseWithBankAccountId()
    {
        $tenant = $this->setUser()->get('tenant');
        $bankAccount = BankAccount::first();
        factory(Expenses::class, 5)->create();
        factory(BankAccountPosting::class, 10)->create(['bank_account_id' => $bankAccount->id]);
        $response = $this->get("$tenant->sub_domain/bank_account/expense/report?bank_account_id=$bankAccount->id");

        $response
            ->assertStatus(200)
            ->assertViewIs('bank_account.report_expense');
    }
}
