<?php


namespace App\Services;


use App\Models\Invoice;
use App\Models\Supplier;
use App\Repositories\InvoiceRepository;
use Exception;
use http\Client;
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
    }

    /**
     * @param string $qrCode
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
        $supplier = (new SupplierService())->getSupplierByDom($dom);

    }
}
