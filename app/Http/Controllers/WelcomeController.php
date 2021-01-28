<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index(){
        return view('welcome');
    }
}
