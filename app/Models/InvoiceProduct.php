<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $product_id
 * @method static Builder|InvoiceProduct newModelQuery()
 * @method static Builder|InvoiceProduct newQuery()
 * @method static Builder|InvoiceProduct query()
 * @method static Builder|InvoiceProduct whereCode($value)
 * @method static Builder|InvoiceProduct whereCreatedAt($value)
 * @method static Builder|InvoiceProduct whereId($value)
 * @method static Builder|InvoiceProduct whereName($value)
 * @method static Builder|InvoiceProduct whereProductId($value)
 * @method static Builder|InvoiceProduct whereQuantity($value)
 * @method static Builder|InvoiceProduct whereTotalValue($value)
 * @method static Builder|InvoiceProduct whereUn($value)
 * @method static Builder|InvoiceProduct whereUnitaryValue($value)
 * @method static Builder|InvoiceProduct whereUpdatedAt($value)
 * @method static Builder|InvoiceProduct whereInvoiceId($value)
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
        'product_id',
    ];

    protected $casts = [
        'quantity' => 'float',
        'unitary_value' => 'float',
        'total_value' => 'float',
        'invoice_id' => 'int',
        'product_id' => 'int',
    ];
}
