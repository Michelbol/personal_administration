<?php


namespace App\Services;

use App\Models\InvoiceProduct;
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
    }

    /**
     * @param InvoiceProduct $invoiceProduct
     * @return Model
     * @throws Exception
     */
    public function createProductByInvoiceProduct(InvoiceProduct $invoiceProduct)
    {
         $product = (new ProductService())
            ->create(
                ['name' => $invoiceProduct->name]
            );
         $this->updateProductId($invoiceProduct, $product->id);
        return $invoiceProduct;
    }
}
