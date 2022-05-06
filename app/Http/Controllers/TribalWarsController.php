<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class TribalWarsController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('tribal.index');
    }

    /**
     * @return Application|Factory|View
     */
    public function collect()
    {
        return view('tribal.collect');
    }
}
