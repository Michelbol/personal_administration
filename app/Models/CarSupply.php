<?php

namespace App\Models;

use App\Models\Enum\FuelEnum;
use App\Scopes\TenantModels;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use DB;

/**
 * App\Models\CarSupply
 *
 * @property int $id
 * @property int $car_id
 * @property float|null $kilometer
 * @property float|null $liters
 * @property float|null $total_paid
 * @property string $date
 * @property int $fuel
 * @property string|null $gas_station
 * @property int|null $tenant_id
 * @property float|null $traveled_kilometers
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
 * @method static Builder|CarSupply whereTraveledKilometers($value)
 * @method static Builder|CarSupply whereFuel($value)
 * @method static Builder|CarSupply whereGasStation($value)
 * @method static Builder|CarSupply whereTenantId($value)
 * @mixin Eloquent
 */
class CarSupply extends Model
{
    use TenantModels, HasFactory;

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
            $this->attributes['kilometer'] = formatReal($value);
        }
    }
    public function setLitersAttribute($value){
        $this->attributes['liters'] = 0;
        if(isset($value)){
            $this->attributes['liters'] = formatReal($value);
        }
    }
    public function setTotalPaidAttribute($value){
        $this->attributes['total_paid'] = 0;
        if(isset($value)){
            $this->attributes['total_paid'] = formatReal($value);
        }
    }
    public function setTraveledKilometersAttribute($value){
        $this->attributes['traveled_kilometers'] = 0;
        if(isset($value)){
            $this->attributes['traveled_kilometers'] = formatReal($value);
        }
    }

    public function getKilometerAttribute($value){
        if(isset($value)){
            return getFormatReal($value);
        }
        return getFormatReal(0);
    }
    public function getLitersAttribute($value){
        if(isset($value)){
            return getFormatReal($value);
        }
        return getFormatReal(0);
    }
    public function getTotalPaidAttribute($value){
        if(isset($value)){
            return getFormatReal($value);
        }
        return getFormatReal(0);
    }
    public function getTraveledKilometersAttribute($value){
        if(isset($value)){
            return getFormatReal($value);
        }
        return getFormatReal(0);
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
            $this->attributes['date'] = formatDataCarbon($value);
        }
    }

    /**
     * @param Carbon $startAt
     * @param Carbon $endAt
     * @param $car_id
     * @return Collection
     */
    static function calcMonthlyValues(Carbon $startAt, Carbon $endAt, $car_id){
        return DB::table('car_supplies')
            ->whereBetween('date', [$startAt, $endAt])
            ->where('car_id', $car_id)
            ->groupBy(DB::raw('YEAR(`date`)'), DB::raw('MONTH(`date`)'))
            ->select(
                DB::raw('sum(total_paid) as total_paid'),
                DB::raw('sum(liters) as liters'),
                DB::raw('sum(traveled_kilometers) as traveled_kilometers'),
                DB::raw('sum(traveled_kilometers)/sum(liters) as average'))
            ->get();
    }

    static function calcGlobalMedia($car_id)
    {
        return CarSupply
            ::whereCarId($car_id)
            ->select(DB::raw('sum(traveled_kilometers)/sum(liters) as average'))
            ->whereNotNull('traveled_kilometers')
            ->first();
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
