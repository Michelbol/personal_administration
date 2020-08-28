<?php

namespace App\Http\Controllers;

use App\Models\Enum\SessionEnum;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param string $message
     */
    public function successMessage(string $message)
    {
        Session::flash('message', ['msg' => $message, 'type' => SessionEnum::success]);
    }

    /**
     * @param string $message
     */
    public function errorMessage(string $message)
    {
        Session::flash('message', ['msg' => $message, 'type' => SessionEnum::error]);
    }

    /**
     * @return JsonResponse
     */
    public function jsonSuccessCreate()
    {
        return response()->json(null, Response::HTTP_CREATED);
    }

    /**
     * @param $content
     * @param null $msg
     * @return JsonResponse
     */
    public function jsonObjectSuccess($content, $msg = null)
    {
        return response()->json(['msg'=> $msg, 'content' => $content], Response::HTTP_OK);
    }

    /**
     * @param $error
     * @param null $msg
     * @return JsonResponse
     */
    public function jsonError($error, $msg = null)
    {
        return response()->json(['msg'=> $msg, 'error' => $error], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
