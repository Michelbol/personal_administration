<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceProductRequest;
use App\Models\InvoiceProduct;
use Illuminate\Http\JsonResponse;

class InvoiceProductController extends Controller
{
    /**
     * @param $tenant
     * @param $id
     * @param InvoiceProductRequest $request
     * @return JsonResponse
     */
    public function update($tenant, $id, InvoiceProductRequest $request)
    {
        $invoiceProduct = InvoiceProduct::find($id);
        $invoiceProduct->product_id = $request->validated()['product_id'];
        $invoiceProduct->save();
        return response()->json(['msg' => 'Produto Alterado com Sucesso']);
    }
}
