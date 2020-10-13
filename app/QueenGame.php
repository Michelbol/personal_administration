<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueenGame extends Model
{
    protected $fillable = [
        'model',
        'white_left',
        'black_left',
        'difficulty',
        'start_game',
        'end_game',
        'type_white',
        'type_black',
        'type_black_machine',
        'type_white_machine',
    ];
}
