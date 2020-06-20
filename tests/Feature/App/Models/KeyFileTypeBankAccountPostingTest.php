<?php

namespace Tests\Feature\App\Models;

use App\Models\KeyFileTypeBankAccountPosting;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\SeedingTrait;
use Tests\TestCase;

class KeyFileTypeBankAccountPostingTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRelations()
    {
        $keyFileTypeBankAccountPosting = KeyFileTypeBankAccountPosting::whereIncomeId(null)->first();

        $this->assertIsObject($keyFileTypeBankAccountPosting->expense);

        $keyFileTypeBankAccountPosting = KeyFileTypeBankAccountPosting::whereExpenseId(null)->first();
        $this->assertIsObject($keyFileTypeBankAccountPosting->income);
    }
}
