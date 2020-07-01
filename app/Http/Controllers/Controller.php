<?php

namespace App\Http\Controllers;

use App\Models\Enum\SessionEnum;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
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
}
