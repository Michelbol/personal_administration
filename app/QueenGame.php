<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\QueenGame
 *
 * @property int $id
 * @property string $model
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $white_left
 * @property int $black_left
 * @property int $difficulty
 * @property int $start_game
 * @property int $end_game
 * @property string|null $type_white
 * @property string|null $type_black
 * @property string|null $type_black_machine
 * @property string|null $type_white_machine
 * @method static Builder|QueenGame newModelQuery()
 * @method static Builder|QueenGame newQuery()
 * @method static Builder|QueenGame query()
 * @method static Builder|QueenGame whereBlackLeft($value)
 * @method static Builder|QueenGame whereCreatedAt($value)
 * @method static Builder|QueenGame whereDifficulty($value)
 * @method static Builder|QueenGame whereEndGame($value)
 * @method static Builder|QueenGame whereId($value)
 * @method static Builder|QueenGame whereModel($value)
 * @method static Builder|QueenGame whereStartGame($value)
 * @method static Builder|QueenGame whereTypeBlack($value)
 * @method static Builder|QueenGame whereTypeBlackMachine($value)
 * @method static Builder|QueenGame whereTypeWhite($value)
 * @method static Builder|QueenGame whereTypeWhiteMachine($value)
 * @method static Builder|QueenGame whereUpdatedAt($value)
 * @method static Builder|QueenGame whereWhiteLeft($value)
 * @mixin \Eloquent
 */
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
