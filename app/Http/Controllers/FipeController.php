<?php

namespace App\Http\Controllers;

use App\Services\FipeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FipeController extends Controller
{
    /**
     * @param $tenant
     * @param $id
     * @return JsonResponse
     */
    public function models($tenant, $id)
    {
        return response()->json(
            (new FipeService())->getModels($id)
        );
    }

    /**
     * @param $tenant
     * @param string $brandId
     * @param string $modelId
     * @return JsonResponse
     */
    public function years($tenant, string $brandId, string $modelId)
    {
        return response()->json(
            (new FipeService())->getYears($brandId, $modelId)
        );
    }

    public function price($tenant, string $brandId, string $modelId, string $year)
    {
        return response()->json(
            (new FipeService())->getPrice($brandId, $modelId, $year)
        );
    }
}
