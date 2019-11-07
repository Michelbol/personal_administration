<?php

namespace App\Models;

use App\Utilitarios;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\CarSupply
 *
 * @property int $id
 * @property int $car_id
 * @property float|null $kilometer
 * @property float|null $liters
 * @property float|null $total_paid
 * @property string $date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CarSupply newModelQuery()
 * @method static Builder|CarSupply newQuery()
 * @method static Builder|CarSupply query()
 * @method static Builder|CarSupply whereCarId($value)
 * @method static Builder|CarSupply whereCreatedAt($value)
 * @method static Builder|CarSupply whereDate($value)
 * @method static Builder|CarSupply whereId($value)
 * @method static Builder|CarSupply whereKilometer($value)
 * @method static Builder|CarSupply whereLiters($value)
 * @method static Builder|CarSupply whereTotalPaid($value)
 * @method static Builder|CarSupply whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CarSupply extends Model
{
    protected $fillable = [
        'id',
        'car_id',
        'kilometer',
        'liters',
        'total_paid',
        'date',
    ];

    public function setKilometerAttribute($value){
        $this->attributes['kilometer'] = 0;
        if(isset($value)){
            $this->attributes['kilometer'] = Utilitarios::formatReal($value);
        }
    }
    public function setLitersAttribute($value){
        $this->attributes['liters'] = 0;
        if(isset($value)){
            $this->attributes['liters'] = Utilitarios::formatReal($value);
        }
    }
    public function setTotalPaidAttribute($value){
        $this->attributes['total_paid'] = 0;
        if(isset($value)){
            $this->attributes['total_paid'] = Utilitarios::formatReal($value);
        }
    }

    public function getKilometerAttribute($value){
        if(isset($value)){
            return Utilitarios::getFormatReal($value);
        }
        return Utilitarios::getFormatReal(0);
    }
    public function getLitersAttribute($value){
        if(isset($value)){
            return Utilitarios::getFormatReal($value);
        }
        return Utilitarios::getFormatReal(0);
    }
    public function getTotalPaidAttribute($value){
        if(isset($value)){
            return Utilitarios::getFormatReal($value);
        }
        return Utilitarios::getFormatReal(0);
    }

    public function getDateAttribute($value){
        if($value){
            $value = strtotime($value);
            return date('d/m/Y', $value);
        }
        return $value;
    }

    public function setDateAttribute($value){
        if($value){
            $this->attributes['date'] = Utilitarios::formatDataCarbon($value);
        }
    }
}