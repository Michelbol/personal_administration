<?php


namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\Supplier;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\NotLoadedException;

class ProductService extends CRUDService
{
    /**
     * @var ProductRepository
     */
    protected $repository;

    public function __construct()
    {
        $this->repository = new ProductRepository();
    }

    /**
     * @var Product
     */
    protected $modelClass = Product::class;

    /**
     * @param Product $model
     * @param array $data
     */
    public function fill(&$model, $data)
    {
        $model->name = $data['name'];
    }

    /**
     * @param InvoiceProduct $invoiceProduct
     * @return Product
     * @throws Exception
     */
    public function convertInvoiceProductIntoProduct(InvoiceProduct $invoiceProduct)
    {
        return $this->create(['name' => $invoiceProduct->name]);
    }
}
