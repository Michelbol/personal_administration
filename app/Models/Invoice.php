<?php

namespace App\Models;

use App\Scopes\TenantModels;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Invoice
 *
 * @property int $id
 * @property string $number
 * @property string $series
 * @property string $emission_at
 * @property string $authorization_protocol
 * @property string $access_key
 * @property string $document
 * @property string $qr_code
 * @property float $taxes
 * @property float $discount
 * @property float $total_products
 * @property float $total_paid
 * @property int $tenant_id
 * @property int $supplier_id
 * @property Carbon $authorization_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Supplier $supplier
 * @method static Builder|Invoice newModelQuery()
 * @method static Builder|Invoice newQuery()
 * @method static Builder|Invoice query()
 * @method static Builder|Invoice whereAccessKey($value)
 * @method static Builder|Invoice whereAuthorizationProtocol($value)
 * @method static Builder|Invoice whereAuthorizationAt($value)
 * @method static Builder|Invoice whereCreatedAt($value)
 * @method static Builder|Invoice whereDiscount($value)
 * @method static Builder|Invoice whereDocument($value)
 * @method static Builder|Invoice whereEmissionAt($value)
 * @method static Builder|Invoice whereId($value)
 * @method static Builder|Invoice whereNumber($value)
 * @method static Builder|Invoice whereQrCode($value)
 * @method static Builder|Invoice whereSeries($value)
 * @method static Builder|Invoice whereTaxes($value)
 * @method static Builder|Invoice whereTotalPaid($value)
 * @method static Builder|Invoice whereTotalProducts($value)
 * @method static Builder|Invoice whereUpdatedAt($value)
 * @method static Builder|Invoice whereTenantId($value)
 * @method static Builder|Invoice whereSupplierId($value)
 * @mixin Eloquent
 */
class Invoice extends Model
{
    use TenantModels;

    protected $fillable = [
        'supplier_id',
        'number',
        'series',
        'emission_at',
        'authorization_protocol',
        'authorization_at',
        'access_key',
        'document',
        'qr_code',
        'taxes',
        'discount',
        'total_products',
        'total_paid',
    ];

    protected $casts = [
        'taxes' => 'float',
        'discount' => 'float',
        'total_products' => 'float',
        'total_paid' => 'float',
        'supplier_id' => 'int'
    ];

    protected $dates = [
        'emission_at',
        'authorization_at',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
