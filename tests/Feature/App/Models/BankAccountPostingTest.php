<?php

namespace Tests\Feature\App\Models;


use App\Models\BankAccountPosting;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TestCase;

class BankAccountPostingTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testBankAccount()
    {
        $bankAccountPosting = factory(BankAccountPosting::class)->create();
        $this->assertIsObject($bankAccountPosting->bankAccount);
        $this->assertIsObject($bankAccountPosting->typeBankAccountPosting);
    }
}
