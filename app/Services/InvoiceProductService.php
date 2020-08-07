<?php


namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\Model;

class InvoiceProductService extends CRUDService
{

    /**
     * @var InvoiceProduct
     */
    protected $modelClass = InvoiceProduct::class;

    /**
     * @param InvoiceProduct $model
     * @param array $data
     */
    public function fill(&$model, $data)
    {
        $model->name = $data['name'];
        $model->un = $data['un'];
        $model->code = $data['code'];
        $model->quantity = $data['quantity'];
        $model->unitary_value = $data['unitary_value'];
        $model->total_value = $data['total_value'];
    }

    public function createMany(array $data)
    {
        return InvoiceProduct::insert($data);
    }

    /**
     * @param InvoiceProduct $invoiceProduct
     * @param int $product_id
     */
    public function updateProductId(InvoiceProduct $invoiceProduct, int $product_id)
    {
        $invoiceProduct->product_id = $product_id;
        $invoiceProduct->save();

        $this->updateProductSupplier($invoiceProduct, Product::find($product_id));
    }

    /**
     * @param InvoiceProduct $invoiceProduct
     * @return Model
     * @throws Exception
     */
    public function createProductByInvoiceProduct(InvoiceProduct $invoiceProduct)
    {
        /**
         * @var $product Product
         */
        $product = (new ProductService())
            ->create(
                ['name' => $invoiceProduct->name]
            );
        $this->updateProductId($invoiceProduct, $product->id);
        $this->updateProductSupplier($invoiceProduct, $product);

        return $invoiceProduct;
    }

    public function updateProductSupplier(InvoiceProduct $invoiceProduct, Product $product)
    {
        (new ProductSupplierService())->countOrCreate([
            'code' => $invoiceProduct->code,
            'un' => $invoiceProduct->un,
            'product_id' => $product->id,
            'supplier_id' => Invoice::select('supplier_id')->find($invoiceProduct->invoice_id)->supplier_id,
        ]);
    }
}
