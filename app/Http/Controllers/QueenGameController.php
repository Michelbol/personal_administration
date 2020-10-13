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
        $model->white_left = $request->get('white_left', 0);
        $model->black_left = $request->get('black_left', 0);
        $model->difficulty = $request->get('difficulty', 0);
        $model->start_game = $request->get('start_game', 0);
        $model->end_game = $request->get('end_game', 0);
        $model->type_white = $request->get('type_white', null);
        $model->type_black = $request->get('type_black', null);
        $model->type_black_machine = $request->get('type_black_machine', null);
        $model->type_white_machine = $request->get('type_white_machine', null);
        $model->save();
    }
}
