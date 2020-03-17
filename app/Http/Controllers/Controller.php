<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param string $message
     */
    public function successMessage(string $message)
    {
        Session::flash('message', ['msg' => $message, 'type' => 'success']);
    }

    /**
     * @param string $message
     */
    public function errorMessage(string $message)
    {
        Session::flash('message', ['msg' => $message, 'type' => 'danger']);
    }
}
