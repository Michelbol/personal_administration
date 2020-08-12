<?php


namespace App\Services;

use App\Models\InvoiceProduct;
use App\Repositories\ProductSupplierRepository;
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
     * @param int $product_supplier_id
     */
    public function updateProductSupplierId(InvoiceProduct $invoiceProduct, int $product_supplier_id)
    {
        $invoiceProduct->product_supplier_id = $product_supplier_id;
        $invoiceProduct->save();
    }

    /**
     * @param InvoiceProduct $invoiceProduct
     * @return Model
     * @throws Exception
     */
    public function createProductByInvoiceProduct(InvoiceProduct $invoiceProduct)
    {

        $productSupplierService = new ProductSupplierService();
        $productSupplier = $productSupplierService->searchProductSupplier(
            $invoiceProduct->code,
            $invoiceProduct->invoice->supplier
        );
        if(!isset($productSupplier)){
            $product = (new ProductService())->convertInvoiceProductIntoProduct($invoiceProduct);
            $productSupplier = $productSupplierService->convertInvoiceProductIntoProductSupplier($invoiceProduct, $product);
        }
        $this->updateProductSupplierId($invoiceProduct, $productSupplier->id);

        return $invoiceProduct;
    }

    public function getProducts(int $invoiceId)
    {
        return InvoiceProduct
            ::whereInvoiceId($invoiceId)
            ->join('product_suppliers as ps', 'ps.id', 'invoice_products.product_supplier_id')
            ->join('products as p', 'p.id', 'ps.product_id')
            ->join('suppliers as s', 's.id', 'ps.supplier_id')
            ->select(
                'invoice_products.*',
                (new ProductSupplierRepository())->queryRawProductName('product_name')
            )
            ->get();
    }
}
