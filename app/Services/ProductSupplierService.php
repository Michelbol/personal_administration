<?php


namespace App\Services;


use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\ProductSupplier;
use App\Models\Supplier;
use App\Repositories\ProductRepository;
use App\Repositories\ProductSupplierRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\NotLoadedException;

class ProductSupplierService extends CRUDService
{
    const classHtml = [
        'name' => '.txtTit2',
        'cod' => '.RCod',
        'qtd' => '.Rqtd',
        'un' => '.RUN',
        'unitary_value' => '.RvlUnit',
        'total_value' => '.txtTit3 .valor'
    ];

    /**
     * @var ProductSupplier
     */
    protected $modelClass = ProductSupplier::class;

    /**
     * @var ProductSupplierRepository
     */
    protected $repository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    public function __construct()
    {
        $this->repository = new ProductSupplierRepository();
        $this->productRepository = new ProductRepository();
    }

    /**
     * @param ProductSupplier $model
     * @param array $data
     */
    public function fill(&$model, $data)
    {
        $model->code = $data['code'];
        $model->un = $data['un'];
        $model->product_id = $data['product_id'];
        $model->supplier_id = $data['supplier_id'];
        $model->brand_id = $data['brand_id'] ?? null;
    }

    /**
     * @param $data
     * @throws Exception
     */
    public function countOrCreate($data)
    {
        $count = $this->repository->countByCodeAndSupplierId($data['code'], $data['supplier_id']);
        if($count === 0){
            $this->create($data);
        }
    }

    public function find($id)
    {
        return $this->findById($id);
    }

    /**
     * @param Dom $dom
     * @param Supplier $supplier
     * @param Invoice $invoice
     * @return array
     * @throws ChildNotFoundException
     * @throws NotLoadedException
     */
    public function findOrCreateProductsByDom(Dom $dom, Supplier $supplier, Invoice $invoice)
    {
        /**
         * @var Dom\HtmlNode $tr
         * @var Product $product
         */
        $trs = $dom->find('#tabResult tr');
        foreach ($trs as $tr){
            $product_supplier_id = null;
            $name = $tr->find($this::classHtml['name'])->text;
            $code = $this->getProductCode($tr->find($this::classHtml['cod'])->text);
            $productSupplier = $this->searchProductSupplier($code, $supplier);
            $un = removeSpaces($tr->find($this::classHtml['un'])->text);
            if(!isset($productSupplier)){
                $product = (new ProductService())->create(['name' => $name]);
                $productSupplier = (new ProductSupplierService())->countOrCreate([
                    'code' => $code,
                    'un' => $un,
                    'product_id' => $product->id,
                    'supplier_id' => $supplier->id,
                ]);
            }

            $invoiceProducts[] = [
                'name' => $name,
                'un' => $un,
                'code' => $code,
                'quantity' => (float) formatReal($tr->find($this::classHtml['qtd'])->text),
                'unitary_value' => (float) formatReal($tr->find($this::classHtml['unitary_value'])->text),
                'total_value' => (float) formatReal($tr->find($this::classHtml['total_value'])->text),
                'invoice_id' => $invoice->id,
                'product_supplier_id' => $productSupplier->id
            ];
        }
        if(isset($invoiceProducts)){
            return $invoiceProducts;
        }
        return [];
    }

    /**
     * @param string $fullCode
     * @return false|string
     */
    public function getProductCode(string $fullCode)
    {
        $fullCode = explode('(Código: ', $fullCode);
        return substr($fullCode[1], 0, -1);
    }

    /**
     * @param string $name
     * @param string $code
     * @param Supplier $supplier
     * @return Product|Model|Builder|object|null
     */
    public function searchProduct(string $name, string $code, Supplier $supplier)
    {
        $product = $this->productRepository->findOneProductByName($name);
        if(isset($product)){
            $product = $this->productRepository->findOneProductBySupplierAndCode($supplier->id, $code);
        }
        return $product;
    }

    public function searchProductSupplier(string $code, Supplier $supplier)
    {
        return $this->repository->findOneCodeAndSupplierId($code, $supplier->id);
    }

    public function convertInvoiceProductIntoProductSupplier(InvoiceProduct $invoiceProduct, Product $product)
    {
        return $this->create([
            'code'=> $invoiceProduct->code,
            'un'=> $invoiceProduct->un,
            'product_id'=> $product->id,
            'supplier_id'=> $invoiceProduct->invoice->supplier_id,
        ]);
    }
}
