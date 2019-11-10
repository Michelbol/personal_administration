<?php

namespace App\Models;

use App\Utilitarios;
use App\Models\Enum\FuelEnum;
use App\Scopes\TenantModels;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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
 * @property int $fuel
 * @property string|null $gas_station
 * @property int|null $tenant_id
 * @method static Builder|CarSupply whereFuel($value)
 * @method static Builder|CarSupply whereGasStation($value)
 * @method static Builder|CarSupply whereTenantId($value)
 * @property float|null $traveled_kilometers
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CarSupply whereTraveledKilometers($value)
 */
class CarSupply extends Model
{
    use TenantModels;

    protected $fillable = [
        'id',
        'date',
        'fuel',
        'liters',
        'car_id',
        'kilometer',
        'total_paid',
        'gas_station',
        'traveled_kilometers'
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
    public function setTraveledKilometersAttribute($value){
        $this->attributes['traveled_kilometers'] = 0;
        if(isset($value)){
            $this->attributes['traveled_kilometers'] = Utilitarios::formatReal($value);
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
    public function getTraveledKilometersAttribute($value){
        if(isset($value)){
            return Utilitarios::getFormatReal($value);
        }
        return Utilitarios::getFormatReal(0);
    }

    public function getFuelAttribute($value){
        return FuelEnum::getName($value);
    }

    public function setFuelAttribute($value){
        $this->attributes['fuel'] = $value;
        if(FuelEnum::isValidName($value)){
            $this->attributes['fuel'] = FuelEnum::getValue($value);
        }
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

    /**
     * @param $year
     * @param $car_id
     * @return array
     */
    static function calcMonthlyTotalPaid($year, $car_id){
        $totalPaidMonthly = [];
        for($i = 1; $i <=12; $i++){
            $totalPaidMonthly[$i] = DB::table('car_supplies')
                ->whereBetween('date', [Carbon::create($year, $i, 1),Carbon::create($year,$i)->endOfMonth()])
                ->where('car_id', $car_id)
                ->sum('total_paid');
        }
        return $totalPaidMonthly;
    }
    /**
     * @param $year
     * @param $car_id
     * @return array
     */
    static function calcMonthlyLiters($year, $car_id){
        $totalLiters = [];
        for($i = 1; $i <=12; $i++){
            $totalLiters[$i] = DB::table('car_supplies')
                ->whereBetween('date', [Carbon::create($year, $i, 1),Carbon::create($year,$i)->endOfMonth()])
                ->where('car_id', $car_id)
                ->sum('liters');
        }
        return $totalLiters;
    }
    /**
     * @param $year
     * @param $car_id
     * @return array
     */
    static function calcMonthlyAverage($year, $car_id){
        $totalTraveledKilometers = [];
        for($i = 1; $i <=12; $i++){
            $totalTraveledKilometers[$i] = DB::table('car_supplies')
                ->whereBetween('date', [Carbon::create($year, $i, 1),Carbon::create($year,$i)->endOfMonth()])
                ->where('car_id', $car_id)
                ->sum('traveled_kilometers');

        }
        return $totalTraveledKilometers;
    }

    function calcTraveledKilometer(){
        if(isset($this->attributes['kilometer'])){
            $lastSupply = CarSupply::where('date', '<', $this->attributes['date'])
                ->orderByDesc('date')
                ->first(['kilometer']);
            if(isset($lastSupply)){
                $km = $lastSupply->getOriginal('kilometer');
                if(isset($km) && $km > 0){
                    $this->traveled_kilometers = floatval($this->attributes['kilometer']) - floatval($km);
                    return $this->traveled_kilometers;
                }
            }
        }
        $this->traveled_kilometers = 0;
        return $this->traveled_kilometers;
    }
}
