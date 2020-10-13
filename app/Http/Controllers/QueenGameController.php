<?php

namespace App\Http\Controllers;

use App\QueenGame;
use Illuminate\Http\Request;

class QueenGameController extends Controller
{
    public function save(Request $request)
    {
        $model = new QueenGame();
        $model->model = json_encode($request->get('model'));
        $model->save();
    }
}
