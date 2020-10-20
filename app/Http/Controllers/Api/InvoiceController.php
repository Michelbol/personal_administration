<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceQrCodeRequest;
use App\Jobs\ReadInvoiceByQrCode;
use App\Services\InvoiceService;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;

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
            ReadInvoiceByQrCode::dispatch($request->validated()['url_qr_code']);
            return $this->jsonSuccessCreate();
        }catch (Exception $e){
            DB::rollBack();
            return $this->jsonError($e->getMessage());
        }
    }
}
