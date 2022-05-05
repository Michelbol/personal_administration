<?php

namespace App\Http\Controllers;

use App\Services\FipeService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;

class FipeController extends Controller
{
    private $fipeService;

    public function __construct(FipeService $fipeService)
    {
        $this->fipeService = $fipeService;
    }

    /**
     * @param $tenant
     * @param $id
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function models($tenant, $id)
    {
        return response()->json(
            $this->fipeService->getModels($id)
        );
    }

    /**
     * @param $tenant
     * @param string $brandId
     * @param string $modelId
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function years($tenant, string $brandId, string $modelId)
    {
        return response()->json(
            $this->fipeService->getYears($brandId, $modelId)
        );
    }

    public function price($tenant, string $brandId, string $modelId, string $year)
    {
        return response()->json(
            $this->fipeService->getPrice($brandId, $modelId, $year)
        );
    }
}
