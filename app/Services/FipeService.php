<?php


namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FipeService
{
    /**
     * @var string
     */
    protected $url = 'https://parallelum.com.br/fipe/api/v1';
    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return mixed
     * @throws GuzzleException
     */
    public function getBrands()
    {
        $response = $this->client->get("$this->url/carros/marcas");
        return json_decode($response->getBody());
    }

    /**
     * @param string $brandId
     * @return mixed
     * @throws GuzzleException
     */
    public function getModels(string $brandId)
    {
        $response = $this->client->get("$this->url/carros/marcas/$brandId/modelos");
        return json_decode($response->getBody())->modelos;
    }

    /**
     * @param string $brandId
     * @param string $modelId
     * @return mixed
     * @throws GuzzleException
     */
    public function getYears(string $brandId, string $modelId)
    {
        $response = $this->client->get("$this->url/carros/marcas/$brandId/modelos/$modelId/anos");
        return json_decode($response->getBody());
    }

    /**
     * @param string $brandId
     * @param string $modelId
     * @param string $year
     * @return mixed
     * @throws GuzzleException
     */
    public function getPrice(string $brandId, string $modelId, string $year)
    {
        $response = $this->client->get("$this->url/carros/marcas/$brandId/modelos/$modelId/anos/$year");
        return json_decode($response->getBody());
    }
}
