<?php


namespace App\Services;

use App\Models\Invoice;
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

    const classHtml = [
        'name' => '.txtTit2',
        'cod' => '.RCod',
        'qtd' => '.Rqtd',
        'un' => '.RUN',
        'unitary_value' => '.RvlUnit',
        'total_value' => '.txtTit3 .valor'
    ];

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
     * @param Dom $dom
     * @param Supplier $supplier
     * @return array
     * @throws ChildNotFoundException
     * @throws NotLoadedException
     * @throws Exception
     */
    public function findOrCreateProductsByDom(Dom $dom, Supplier $supplier, Invoice $invoice)
    {
        /**
         * @var Dom\HtmlNode $tr
         * @var Product $product
         */
        $trs = $dom->find('#tabResult tr');
        foreach ($trs as $tr){
            $product_id = null;
            $name = $tr->find($this::classHtml['name'])->text;
            $code = $this->getProductCode($tr->find($this::classHtml['cod'])->text);
            $product = $this->searchProduct($name, $code, $supplier);
            $un = removeSpaces($tr->find($this::classHtml['un'])->text);
            if(isset($product)){
                (new ProductSupplierService())->countOrCreate([
                    'code' => $code,
                    'un' => $un,
                    'product_id' => $product->id,
                    'supplier_id' => $supplier->id,
                ]);
                $product_id = $product->id;
            }

            $invoiceProducts[] = [
                'name' => $name,
                'un' => $un,
                'code' => $code,
                'quantity' => (float) formatReal($tr->find($this::classHtml['qtd'])->text),
                'unitary_value' => (float) formatReal($tr->find($this::classHtml['unitary_value'])->text),
                'total_value' => (float) formatReal($tr->find($this::classHtml['total_value'])->text),
                'invoice_id' => $invoice->id,
                'product_id' => $product_id
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
        $product = $this->repository->findOneProductByName($name);
        if(isset($product)){
            $product = $this->repository->findOneProductBySupplierAndCode($supplier->id, $code);
        }
        return $product;
    }
}
