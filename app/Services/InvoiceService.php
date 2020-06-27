<?php


namespace App\Services;


use App\Models\Invoice;
use App\Models\Supplier;
use App\Repositories\InvoiceRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\CurlException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;

class InvoiceService extends CRUDService
{
    /**
     * @var Invoice
     */
    protected $modelClass = Invoice::class;

    /**
     * @param Invoice $model
     * @param array $data
     */
    public function fill(&$model, $data)
    {
        $model->number = $data['number'];
        $model->series = $data['series'];
        $model->emission_at = $data['emission_at'];
        $model->authorization_protocol = $data['authorization_protocol'];
        $model->authorization_at = $data['authorization_at'];
        $model->access_key = $data['access_key'];
        $model->document = $data['document'];
        $model->qr_code = $data['qr_code'];
        $model->taxes = $data['taxes'];
        $model->discount = $data['discount'];
        $model->total_products = $data['total_products'];
        $model->total_paid = $data['total_paid'];
        $model->supplier_id = $data['supplier_id'];
    }

    /**
     * @param string $qrCode
     * @return Invoice|Model
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws CurlException
     * @throws StrictException
     * @throws Exception
     */
    public function createInvoiceByQrCode(string $qrCode)
    {
        $dom = new Dom();
        $dom = $dom->load($qrCode);
        $supplier = (new SupplierService())->findOrCreateSupplierByDom($dom);
        $invoice = $this->createInvoiceByDom($dom, $supplier, $qrCode);
        $invoiceProducts = (new ProductService())->findOrCreateProductsByDom($dom, $supplier, $invoice);
        (new InvoiceProductService())->createMany($invoiceProducts);
        return $invoice;
    }

    /**
     * @param Dom $dom
     * @param Supplier $supplier
     * @param string $qrCode
     * @return Model|Invoice
     * @throws ChildNotFoundException
     * @throws NotLoadedException
     * @throws Exception
     */
    public function createInvoiceByDom(Dom $dom, Supplier $supplier, string $qrCode)
    {
        /**
         * @var Dom\HtmlNode $li
         */
        $li = $dom->find('#infos li')[0]->getChildren();
        $authorizationData = explode(' ', $li[14]);
        $number = removeSpaces($li[6]->text);
        $series = removeSpaces($li[8]->text);
        $count = (new InvoiceRepository())->countInvoiceByNumberAndSupplierAndSeries($number, $supplier->id,$series );

        if($count > 0){
            throw new Exception('Essa nota já foi inserida.');
        }

        $data = [
            'supplier_id' => $supplier->id,
            'number' => removeSpaces($li[6]->text),
            'series' => removeSpaces($li[8]->text),
            'emission_at' => Carbon::createFromFormat('d/m/Y H:i:s', explode(' - ', $li[10]->text)[0]),
            'authorization_protocol' => $authorizationData[0],
            'authorization_at' => Carbon::createFromFormat('d/m/Y H:i:s', $authorizationData[1]." ".$authorizationData[2]),
            'access_key' => removeSpaces($dom->find('#infos li .chave')->text),
            'document' => cleanNumber($dom->find('#infos li')[2]->text),
            'qr_code' => $qrCode,
            'taxes' => (float) formatReal($dom->find('.totalNumb.txtObs')->text),
            'discount' => (float) 0,
            'total_products' => (float) formatReal($dom->find('.totalNumb.txtMax')->text),
            'total_paid' => (float) formatReal($dom->find('#totalNota #linhaTotal')[2]->getChildren()[3]->text),
        ];
        return $this->create($data);
    }
}