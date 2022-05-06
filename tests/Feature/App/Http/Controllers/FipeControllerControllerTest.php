<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Services\FipeService;
use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery;
use Tests\SeedingTrait;
use Tests\TestCase;

class FipeControllerControllerTest extends TestCase
{
    use DatabaseMigrations, SeedingTrait;

    private $responseModels = [
        [
            'nome' => 'Amarok',
            'codigo' => '5585'
        ]
    ];

    private $responseYears = [
        [
            'nome' => '32000 Diesel',
            'codigo' => '32000-3'
        ]
    ];

    private $responsePrice = [
        [
            'Valor' => 'R$ 127.739,00',
            'Marca' => 'VW - VolksWagen',
            'Modelo' => 'Amarok',
            'AnoModelo' => 2014,
            'Combustivel' => 'Diesel',
            'CodigoFipe' => '005340-6',
            'MesReferencia' => 'maio de 2022 ',
            'TipoVeiculo' => 1,
            'SiglaCombustivel' => 'D',
        ]
    ];

    /**
     *
     * @return void
     * @throws Exception
     */
    public function testModels()
    {
        $fipeService = Mockery::mock(FipeService::class)
            ->shouldReceive('getModels')
            ->once()
            ->andReturn($this->responseModels)
            ->getMock();

        $this->instance(FipeService::class, $fipeService);

        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $id = random_int(0, 9999999);
        $response = $this->get("$tenant->sub_domain/fipe/models/$id");

        $response
            ->assertStatus(200)
            ->assertJson($this->responseModels);
    }

    /**
     *
     * @return void
     * @throws Exception
     */
    public function testYears()
    {
        $fipeService = Mockery::mock(FipeService::class)
            ->shouldReceive('getYears')
            ->once()
            ->andReturn($this->responseYears)
            ->getMock();

        $this->instance(FipeService::class, $fipeService);

        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $brandId = random_int(0, 9999999);
        $modelId = random_int(0, 9999999);
        $response = $this->get("$tenant->sub_domain/fipe/years/$brandId/$modelId");

        $response
            ->assertStatus(200)
            ->assertJson($this->responseYears);
    }

    /**
     *
     * @return void
     * @throws Exception
     */
    public function testPrice()
    {
        $fipeService = Mockery::mock(FipeService::class)
            ->shouldReceive('getPrice')
            ->once()
            ->andReturn($this->responsePrice)
            ->getMock();

        $this->instance(FipeService::class, $fipeService);

        $object = $this->setUser();
        $tenant = $object->get('tenant');
        $brandId = random_int(0, 9999999);
        $modelId = random_int(0, 9999999);
        $yearId = random_int(0, 9999999);
        $response = $this->get("$tenant->sub_domain/fipe/price/$brandId/$modelId/$yearId");

        $response
            ->assertStatus(200)
            ->assertJson($this->responsePrice);
    }
}
