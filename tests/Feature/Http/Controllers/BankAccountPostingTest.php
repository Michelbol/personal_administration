<?php

namespace Tests\Feature;

use App\Models\BankAccount;
use App\Models\BankAccountPosting;
use App\Models\Enum\SessionEnum;
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
    public function testIndexPostingByBank()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $bankAccountId = BankAccount::first()->id;
        $response = $this->get("$tenant->sub_domain/bank_account_posting/$bankAccountId");

        $response
            ->assertStatus(200)
            ->assertViewIs('bank_account_posting.index');
    }

    public function testStore()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $data = factory(BankAccountPosting::class)->make()->toArray();
        $data['posting_date'] = ($data['posting_date'])->format('d/m/Y H:i');
        $response = $this->post("$tenant->sub_domain/bank_account_posting", $data);
        $bankAccountPosting = BankAccountPosting
            ::whereBankAccountId($data['bank_account_id'])
            ->whereDocument($data['document'])
            ->whereType($data['type'])
            ->whereTypeBankAccountPostingId($data['type_bank_account_posting_id'])
            ->first();
        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/bank_account_posting/$bankAccountPosting->bank_account_id")
            ->assertSessionHas('message', ['msg' => 'Lançamento Salvo com sucesso', 'type' => SessionEnum::success]);
    }

    public function testShow()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $bankAccountPosting = factory(BankAccountPosting::class)->create();
        $response = $this->get("$tenant->sub_domain/bank_account_posting/show/$bankAccountPosting->id");
        $response
            ->assertStatus(200)
            ->assertJson(['result' => true, 'bankAccountPosting' => []]);
    }

    public function testUpdate()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $data = factory(BankAccountPosting::class)->create()->toArray();
        $data['posting_date'] = ($data['posting_date'])->format('d/m/Y H:i');
        $response = $this->put("$tenant->sub_domain/bank_account_posting/{$data['id']}", $data);
        $bankAccountPosting = BankAccountPosting
            ::whereBankAccountId($data['bank_account_id'])
            ->whereDocument($data['document'])
            ->whereType($data['type'])
            ->whereTypeBankAccountPostingId($data['type_bank_account_posting_id'])
            ->first();
        $response
            ->assertStatus(302)
            ->assertRedirect("$tenant->sub_domain/bank_account_posting/$bankAccountPosting->bank_account_id")
            ->assertSessionHas('message', ['msg' => 'Lançamento Salvo com sucesso', 'type' => SessionEnum::success]);
    }

    public function testDestroy()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $data = factory(BankAccountPosting::class)->create()->toArray();
        $response = $this->delete("$tenant->sub_domain/bank_account_posting/{$data['id']}");
        $response
            ->assertStatus(302)
            ->assertRedirect('')
            ->assertSessionHas('message', ['msg'=>'Lançamento deletado com sucesso', 'type' => SessionEnum::success]);
    }
}
