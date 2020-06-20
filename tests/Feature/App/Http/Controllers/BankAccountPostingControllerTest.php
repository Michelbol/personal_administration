<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankAccountPosting;
use App\Models\Enum\SessionEnum;
use App\Models\Enum\TypeBankAccountPostingEnum;
use App\Models\TypeBankAccountPosting;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\SeedingTrait;
use Tests\TestCase;

class BankAccountPostingControllerTest extends TestCase
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
        /**
         * @var $data array
         */
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
        /**
         * @var $data array
         */
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

        /**
         * @var $data Collection
         */
        $data = factory(BankAccountPosting::class, 2)->create();
        $data2 = $data->sortByDesc('posting_date')->first();

        $response = $this->delete("$tenant->sub_domain/bank_account_posting/{$data2['id']}");
        $response
            ->assertStatus(302)
            ->assertRedirect('')
            ->assertSessionHas('message', ['msg'=>'Lançamento deletado com sucesso', 'type' => SessionEnum::success]);

        /**
         * @var $data Collection
         */
        $data = factory(BankAccountPosting::class, 5)->create(['type' => TypeBankAccountPostingEnum::CREDIT]);
        $data->merge(factory(BankAccountPosting::class, 5)->create(['type' => TypeBankAccountPostingEnum::DEBIT]));

        $data2 = $data->sortBy('posting_date')->last();

        $response = $this->delete("$tenant->sub_domain/bank_account_posting/{$data2['id']}");
        $response
            ->assertStatus(302)
            ->assertRedirect('')
            ->assertSessionHas('message', ['msg'=>'Lançamento deletado com sucesso', 'type' => SessionEnum::success]);

    }

    public function testReadFileStore()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $files = [
            'arquivostxt' => [
                $this->createFile('2018março.txt', 'text/plain'),
                $this->createFile('2018julho.txt', 'text/plain')
            ],
            'arquivosofx' => [
                $this->createFile('2018-05Maio.ofx', 'text/plain'),
                $this->createFile('2018-06Junho.ofx', 'text/plain'),
            ]
        ];
        $data = [];

        $server = $this->transformHeadersToServerVars($data);

        $response = $this->call(
            'POST',
            "$tenant->sub_domain/bank_account_posting/read_file",
            $data,
            [],
            $files,
            $server
        );

        $response
            ->assertStatus(302)
            ->assertRedirect("/$tenant->sub_domain/bank_account_posting")
            ->assertSessionHas('message', ['msg'=>'Arquivo(s) Lido(s) Com Sucesso', 'type' => SessionEnum::success]);
    }

    public function testReadFileStoreWrongOfx()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $files = [
            'arquivosofx' => [
                $this->createFile('WRONG.ofx', 'text/plain'),
            ]
        ];
        $data = [];

        $server = $this->transformHeadersToServerVars($data);

        $response = $this->call(
            'POST',
            "$tenant->sub_domain/bank_account_posting/read_file",
            $data,
            [],
            $files,
            $server
        );

        $response
            ->assertStatus(302)
            ->assertRedirect("")
            ->assertSessionHas('message', ['msg'=>'\nExistem tipos não salvos: REM BASICAAA', 'type' => SessionEnum::error]);
    }

    public function testReadFileStoreWrongTxt()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $files = [
            'arquivostxt' => [
                $this->createFile('WRONG.txt', 'text/plain'),
            ]
        ];
        $data = [];

        $server = $this->transformHeadersToServerVars($data);

        $response = $this->call(
            'POST',
            "$tenant->sub_domain/bank_account_posting/read_file",
            $data,
            [],
            $files,
            $server
        );

        $response
            ->assertStatus(302)
            ->assertRedirect("")
            ->assertSessionHas('message', ['msg'=>'\nExistem tipos não salvos: REM BASICAAA', 'type' => SessionEnum::error]);
    }

    public function testReadFileStoreWrongTxtHeader()
    {
        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $files = [
            'arquivostxt' => [
                $this->createFile('WRONG-HEADER.txt', 'text/plain'),
            ]
        ];
        $data = [];

        $server = $this->transformHeadersToServerVars($data);

        $response = $this->call(
            'POST',
            "$tenant->sub_domain/bank_account_posting/read_file",
            $data,
            [],
            $files,
            $server
        );

        $response
            ->assertStatus(302)
            ->assertRedirect("")
            ->assertSessionHas('message', ['msg'=>'Arquivo inválido', 'type' => SessionEnum::error]);
    }

    public function testFile()
    {
        $subDomain = $this->setUser()->get('tenant')->sub_domain;

        $response = $this->get("$subDomain/bank_account_posting");

        $response
            ->assertStatus(200)
            ->assertViewIs('bank_account_posting.file');
    }

    public function testGet()
    {
        $subDomain = $this->setUser()->get('tenant')->sub_domain;
        $bankAccount = factory(BankAccount::class)->create();
        factory(BankAccountPosting::class,10)->create(['bank_account_id' => $bankAccount->id]);

        $response = $this->get("$subDomain/bank_account_posting/get/$bankAccount->id?type=0");

        $response
            ->assertStatus(200);

        $response = $this->get("$subDomain/bank_account_posting/get/$bankAccount->id");

        $response
            ->assertStatus(200);

        $typeName = TypeBankAccountPosting::inRandomOrder()->first()->id;
        $response = $this->get("$subDomain/bank_account_posting/get/$bankAccount->id?type_name=$typeName");

        $response
            ->assertStatus(200);

        $startPostingDate = now()->format('d/m/Y H:i');
        $endPostingDate = now()->addDay()->format('d/m/Y H:i');
        $response = $this->get("$subDomain/bank_account_posting/get/$bankAccount->id?posting_date=$startPostingDate - $endPostingDate");

        $response
            ->assertStatus(200);
    }

    public function createFile($name, $mime)
    {
        return new UploadedFile(
            base_path()."\\tests\\Feature\\App\\Http\\Controllers\\$name",
            $name,
            $mime,
            null,
            true
        );
    }
}
