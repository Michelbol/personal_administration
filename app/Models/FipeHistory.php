<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\FipeHistory
 *
 * @property int $id
 * @property float $value
 * @property string $consultation_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $car_id
 * @method static Builder|FipeHistory newModelQuery()
 * @method static Builder|FipeHistory newQuery()
 * @method static Builder|FipeHistory query()
 * @method static Builder|FipeHistory whereCarId($value)
 * @method static Builder|FipeHistory whereConsultationDate($value)
 * @method static Builder|FipeHistory whereCreatedAt($value)
 * @method static Builder|FipeHistory whereId($value)
 * @method static Builder|FipeHistory whereUpdatedAt($value)
 * @method static Builder|FipeHistory whereValue($value)
 * @mixin Eloquent
 */
class FipeHistory extends Model
{
    protected $fillable = [
        'consultation_date',
        'value',
        'car_id'
    ];

    protected $casts = [
      'car_id' => 'int',
      'consultation_date' => 'datetime',
      'value' => 'float',
    ];
}
