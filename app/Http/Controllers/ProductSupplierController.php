<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductSupplierRequest;
use App\Models\Brand;
use App\Models\ProductSupplier;
use App\Models\Supplier;
use App\Services\ProductSupplierService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
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
     * Display a listing of the resource.
     *
     * @param $tenant
     * @param Request $request
     * @return Factory|View
     */
    public function index($tenant, Request $request)
    {
        return redirect()->routeTenant('product.index');
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
            ->join('suppliers as s', 's.id', 'product_suppliers.supplier_id')
            ->leftJoin('brands as b', 'b.id', 'product_suppliers.brand_id')
            ->select('product_suppliers.*', 's.fantasy_name', 'b.name as brand_name');

        $response = DataTables::of($model)
            ->addColumn('actions', function ($model) {
                return getBtnAction([
                    ['type' => 'edit', 'url' => '#', 'id' => $model->id],
                    ['type' => 'delete', 'url' => routeTenant('product_supplier.destroy', [$model->id]), 'id' => $model->id]
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

    /**
     * Show the form for creating a new resource.
     *
     * @param $tenant
     * @return Factory|View
     */
    public function create($tenant)
    {
        $suppliers = Supplier::all();
        $brands = Brand::all();
        return view("product_supplier.create",
            compact(
                'suppliers',
                'brands'
            ));
    }
}
