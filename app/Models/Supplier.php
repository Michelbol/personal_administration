<?php

namespace App\Models;

use App\Scopes\TenantModels;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Supplier
 *
 * @property int $id
 * @property string $company_name
 * @property string $fantasy_name
 * @property string $cnpj
 * @property string $address
 * @property string $address_number
 * @property string $complement
 * @property string $neighborhood
 * @property string $city
 * @property string $state
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $tenant_id
 * @method static Builder|Supplier newModelQuery()
 * @method static Builder|Supplier newQuery()
 * @method static Builder|Supplier query()
 * @method static Builder|Supplier whereAddress($value)
 * @method static Builder|Supplier whereAddressNumber($value)
 * @method static Builder|Supplier whereCity($value)
 * @method static Builder|Supplier whereCnpj($value)
 * @method static Builder|Supplier whereCompanyName($value)
 * @method static Builder|Supplier whereCreatedAt($value)
 * @method static Builder|Supplier whereFantasyName($value)
 * @method static Builder|Supplier whereId($value)
 * @method static Builder|Supplier whereNeighborhood($value)
 * @method static Builder|Supplier whereState($value)
 * @method static Builder|Supplier whereTenantId($value)
 * @method static Builder|Supplier whereUpdatedAt($value)
 * @method static Builder|Supplier whereComplement($value)
 * @mixin Eloquent
 */
class Supplier extends Model
{
    use TenantModels, HasFactory;

    protected $fillable = [
        'company_name',
        'fantasy_name',
        'cnpj',
        'address',
        'address_number',
        'neighborhood',
        'city',
        'state',
        'tenant_id'
    ];

    public function setCnpjAttribute($value)
    {
        if(strpos('-', $value)){
            $value = cleanNumber($value);
        }
        $this->attributes['cnpj'] = $value;
    }
}
