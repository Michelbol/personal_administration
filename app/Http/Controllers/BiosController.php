<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BiosController extends Controller
{
    public function index(){
        return view('bios.index');
    }
}
