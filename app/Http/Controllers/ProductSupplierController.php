<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductSupplierRequest;
use App\Models\ProductSupplier;
use App\Services\ProductSupplierService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Yajra\DataTables\DataTables;
use Exception;

class ProductSupplierController extends CrudController
{

    protected $msgStore = 'Fornecedor incluido com sucesso';

    protected $msgUpdate = 'Fornecedor atualizado com sucesso';

    protected $msgDestroy = 'Fornecedor deletado com sucesso';

    protected $requestValidator = ProductSupplierRequest::class;

    public function __construct(ProductSupplierService $service = null, ProductSupplier $model = null)
    {
        parent::__construct($service, $model);
    }

    /**
     * @param Request $request
     * @param string $id
     * @param string $tenant
     * @return mixed
     * @throws Exception
     */
    public function get(Request $request, $tenant, $id)
    {
        $model = ProductSupplier
            ::whereProductId($id)
            ->join('suppliers as s', 's.id', 'product_suppliers.supplier_id');

        $response = DataTables::of($model)
            ->addColumn('actions', function ($model) {
                return getBtnAction([
                    ['type' => 'edit', 'url' => '#', 'id' => $model->id],
                    ['type' => 'delete', 'url' => routeTenant('bank_account_posting.destroy', [$model->id]), 'id' => $model->id]
                ]);
            })
            ->rawColumns(['actions', 'isFixed'])
            ->toJson();
        return $response->original;
    }
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param $tenant
     * @return JsonResponse
     */
    public function show($tenant, $id)
    {
        return response()->json
        (
            [
                "result" => true,
                "productSupplier" => $this->service->find($id)
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $tenant
     * @param Request $request
     * @param $id
     * @return Response|RedirectResponse
     */
    public function update($tenant, Request $request, $id)
    {
        /**
         * @var $validator FormRequest
         */
        try{
            if(isset($this->requestValidator)){
                $validator = new $this->requestValidator();
                if(!$validator->authorize()){
                    throw new UnauthorizedException;
                }
                $this->validate($request, $validator->rules(), $validator->messages());
            }
            $this->service->update($id, $request->all());
            $this->successMessage($this->msgUpdate);
            return redirect()->routeTenant("product.index");
        }catch (Exception $e){
            $this->errorMessage($e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
    }
}
