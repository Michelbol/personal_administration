<?php

namespace App\Models;

use App\Scopes\TenantModels;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Car
 *
 * @mixin Eloquent
 * @property int $id
 * @property string $license_plate
 * @property string|null $model
 * @property string|null $brand
 * @property string|null $year
 * @property string|null $annual_licensing
 * @property string|null $annual_insurance
 * @property int $tenant_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|CarSupply[] $carSupplies
 * @property-read int|null $car_supplies_count
 * @method static Builder|Car whereAnnualInsurance($value)
 * @method static Builder|Car whereAnnualLicensing($value)
 * @method static Builder|Car whereCreatedAt($value)
 * @method static Builder|Car whereId($value)
 * @method static Builder|Car whereLicensePlate($value)
 * @method static Builder|Car whereModel($value)
 * @method static Builder|Car whereUpdatedAt($value)
 * @method static Builder|Car whereTenantId($value)
 * @method static Builder|Car whereBrand($value)
 * @method static Builder|Car whereYear($value)
 * @method static Builder|Car newModelQuery()
 * @method static Builder|Car newQuery()
 * @method static Builder|Car query()
 */
class Car extends Model
{
    use TenantModels, HasFactory;

    protected $fillable = [
        'id',
        'model',
        'brand',
        'year',
        'license_plate',
        'annual_licensing',
        'annual_insurance'
    ];

    public function getAnnualLicensingAttribute($value){
        if($value){
            $value = strtotime($value);
            return date('d/m/Y', $value);
        }
        return $value;
    }

    public function getAnnualInsuranceAttribute($value){
        if($value){
            $value = strtotime($value);
            return date('d/m/Y', $value);
        }
        return $value;
    }

    public function setAnnualInsuranceAttribute($value){
        if($value){
            $this->attributes['annual_insurance'] = formatDataCarbon($value);
        }
    }

    public function setAnnualLicensingAttribute($value){
        if($value){
            $this->attributes['annual_licensing'] = formatDataCarbon($value);
        }
    }

    public function carSupplies(){
        return $this->hasMany(CarSupply::class);
    }
}
