<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceQrCodeRequest;
use App\Models\Invoice;
use App\Services\InvoiceService;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * @var InvoiceService
     */
    private $service;

    /**
     * InvoiceController constructor.
     * @param InvoiceService $service
     */
    public function __construct(InvoiceService $service)
    {
        $this->service = $service;
    }

    /**
     * @param InvoiceQrCodeRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function storeByQrCode(InvoiceQrCodeRequest $request)
    {
        try {
            DB::beginTransaction();
            $invoice = $this->service->createInvoiceByQrCode($request->validated()['url_qr_code']);
            DB::commit();
            return $this->jsonObjectSuccess($invoice);
        }catch (Exception $e){
            DB::rollBack();
            return $this->jsonError($e->getMessage());
        }
    }
}
