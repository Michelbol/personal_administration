<?php

namespace App\Http\Controllers;


use App\Http\Requests\CarRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductSupplier;
use App\Models\Supplier;
use App\Services\ProductService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class ProductController extends CrudController
{
    protected $msgStore = 'Produto incluido com sucesso';

    protected $msgUpdate = 'Produto atualizado com sucesso';

    protected $msgDestroy = 'Produto deletado com sucesso';

    protected $requestValidator = ProductRequest::class;

    public function __construct(ProductService $service = null, Product $model = null)
    {
        parent::__construct($service, $model);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function get(){
        $model = Product::select('*');

        $response = DataTables::of($model)
            ->addColumn('actions', function ($model){
                return getBtnAction([
                    ['type'=>'edit',    'url' => routeTenant('product.edit',[$model->id])],
                    ['type'=>'delete',  'url' => routeTenant('product.destroy',[$model->id]), 'id' => $model->id]
                ]);
            })
            ->rawColumns(['actions'])
            ->toJson();
        return $response->original;
    }

    /**
     * @param $tenant
     * @param $id
     * @param Request $request
     * @return Factory|View
     */
    public function edit($tenant, $id, Request $request){
        $product = $this->model::findOrFail($id);
        $productSuppliers = ProductSupplier::whereProductId($id)->with('supplier')->get();
        $suppliers = Supplier::all();
        $data = [
            'product' => $product,
            'productSuppliers' => $productSuppliers,
            'suppliers' => $suppliers
        ];
        return view("product.edit", $data);
    }
}
