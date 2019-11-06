<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Car
 *
 * @method static Builder|Car newModelQuery()
 * @method static Builder|Car newQuery()
 * @method static Builder|Car query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $license_plate
 * @property string|null $model
 * @property string|null $annual_licensing
 * @property string|null $annual_insurance
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Car whereAnnualInsurance($value)
 * @method static Builder|Car whereAnnualLicensing($value)
 * @method static Builder|Car whereCreatedAt($value)
 * @method static Builder|Car whereId($value)
 * @method static Builder|Car whereLicensePlate($value)
 * @method static Builder|Car whereModel($value)
 * @method static Builder|Car whereUpdatedAt($value)
 */
class Car extends Model
{
    protected $fillable = [
        'id',
        'model',
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
            $value = strtotime($value);
            $this->attributes['annual_insurance'] = date('Y-m-d', $value);
        }
    }

    public function setAnnualLicensingAttribute($value){
        if($value){
            $value = strtotime($value);
            $this->attributes['annual_licensing'] = date('Y-m-d', $value);
        }
    }
}
