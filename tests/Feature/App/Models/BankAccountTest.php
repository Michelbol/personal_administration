<?php

namespace Tests\Feature\App\Models;

use App\Models\BankAccount;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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
    public function testBankAccountPosting()
    {
        $bankAccount = BankAccount::first();
        $this->assertIsIterable($bankAccount->bankAccountPostings);
    }
}
