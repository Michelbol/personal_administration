<?php


namespace App\Services;


use GuzzleHttp\Client;

class FipeService
{
    /**
     * @var string
     */
    protected $url = 'https://fipeapi.appspot.com/api/1';
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
        $response = $this->client->get("$this->url/carros/marcas.json");
        return json_decode($response->getBody());
    }

    /**
     * @param string $brandId
     * @return mixed
     */
    public function getModels(string $brandId)
    {
        $response = $this->client->get("$this->url/carros/veiculos/$brandId.json");
        return json_decode($response->getBody());
    }

    /**
     * @param string $brandId
     * @param string $modelId
     * @return mixed
     */
    public function getYears(string $brandId, string $modelId)
    {
        $response = $this->client->get("$this->url/carros/veiculo/$brandId/$modelId.json");
        return json_decode($response->getBody());
    }

    public function getPrice(string $brandId, string $modelId, string $year)
    {
        $response = $this->client->get("$this->url/carros/veiculo/$brandId/$modelId/$year.json");
        return json_decode($response->getBody());
    }
}
