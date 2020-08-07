<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceProductRequest;
use App\Models\InvoiceProduct;
use App\Services\InvoiceProductService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class InvoiceProductController extends Controller
{

    /**
     * @var InvoiceProductService
     */
    private $service;

    public function __construct(InvoiceProductService $service)
    {
        $this->service = $service;
    }

    /**
     * @param $tenant
     * @param $id
     * @param InvoiceProductRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function update($tenant, $id, InvoiceProductRequest $request)
    {
        $invoiceProduct = InvoiceProduct::find($id);
        $this->service->updateProductId($invoiceProduct, $request->validated()['product_id']);

        return response()->json(['msg' => 'Produto Alterado com Sucesso']);
    }

    /**
     * @param $tenant
     * @param InvoiceProduct $invoiceProduct
     * @return RedirectResponse
     * @throws Exception
     */
    public function createProductByInvoiceProduct($tenant, InvoiceProduct $invoiceProduct)
    {
        $this->service->createProductByInvoiceProduct($invoiceProduct);
        return redirect()->routeTenant('invoice.edit', [$invoiceProduct->invoice_id]);
    }
}
