<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\ProductSupplier
 *
 * @property int $id
 * @property string $code
 * @property string $un
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $product_id
 * @property int $supplier_id
 * @method static Builder|ProductSupplier newModelQuery()
 * @method static Builder|ProductSupplier newQuery()
 * @method static Builder|ProductSupplier query()
 * @method static Builder|ProductSupplier whereCode($value)
 * @method static Builder|ProductSupplier whereCreatedAt($value)
 * @method static Builder|ProductSupplier whereId($value)
 * @method static Builder|ProductSupplier whereProductId($value)
 * @method static Builder|ProductSupplier whereSupplierId($value)
 * @method static Builder|ProductSupplier whereUn($value)
 * @method static Builder|ProductSupplier whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ProductSupplier extends Model
{
    protected $fillable = [
        'code',
        'un',
        'product_id',
        'supplier_id',
    ];

    protected $casts = [
        'product_id' => 'int',
        'supplier_id' => 'int',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
