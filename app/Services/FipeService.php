<?php


namespace App\Services;


use GuzzleHttp\Client;

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

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @return mixed
     */
    public function getBrands()
    {
        $response = $this->client->get("$this->url/carros/marcas");
        return json_decode($response->getBody());
    }

    /**
     * @param string $brandId
     * @return mixed
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
     */
    public function getYears(string $brandId, string $modelId)
    {
        $response = $this->client->get("$this->url/carros/marcas/$brandId/modelos/$modelId/anos");
        return json_decode($response->getBody());
    }

    public function getPrice(string $brandId, string $modelId, string $year)
    {
        $response = $this->client->get("$this->url/carros/marcas/$brandId/modelos/$modelId/anos/$year");
        return json_decode($response->getBody());
    }
}
