<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\InvoiceProduct
 *
 * @property int $id
 * @property string $name
 * @property string $un
 * @property string $code
 * @property float $quantity
 * @property float $unitary_value
 * @property float $total_value
 * @property int $invoice_id
 * @property int $product_supplier_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Invoice $invoice
 * @property-read ProductSupplier $productSupplier
 * @method static Builder|InvoiceProduct newModelQuery()
 * @method static Builder|InvoiceProduct newQuery()
 * @method static Builder|InvoiceProduct query()
 * @method static Builder|InvoiceProduct whereCode($value)
 * @method static Builder|InvoiceProduct whereCreatedAt($value)
 * @method static Builder|InvoiceProduct whereId($value)
 * @method static Builder|InvoiceProduct whereName($value)
 * @method static Builder|InvoiceProduct whereQuantity($value)
 * @method static Builder|InvoiceProduct whereTotalValue($value)
 * @method static Builder|InvoiceProduct whereUn($value)
 * @method static Builder|InvoiceProduct whereUnitaryValue($value)
 * @method static Builder|InvoiceProduct whereUpdatedAt($value)
 * @method static Builder|InvoiceProduct whereInvoiceId($value)
 * @method static Builder|InvoiceProduct whereProductSupplierId($value)
 * @mixin Eloquent
 */
class InvoiceProduct extends Model
{
    protected $fillable = [
        'name',
        'un',
        'code',
        'quantity',
        'unitary_value',
        'total_value',
        'invoice_id',
        'product_supplier_id',
    ];

    protected $casts = [
        'quantity' => 'float',
        'unitary_value' => 'float',
        'total_value' => 'float',
        'invoice_id' => 'int',
        'product_supplier_id' => 'int',
    ];

    /**
     * @return BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * @return BelongsTo
     */
    public function productSupplier()
    {
        return $this->belongsTo(ProductSupplier::class);
    }
}
