<?php

namespace App\Http\Controllers;


use App\Http\Requests\CarRequest;
use App\Models\Invoice;
use App\Models\Product;
use App\Services\ProductService;
use Exception;
use Yajra\DataTables\DataTables;

class ProductController extends CrudController
{
    protected $msgStore = 'Produto incluido com sucesso';

    protected $msgUpdate = 'Produto atualizado com sucesso';

    protected $msgDestroy = 'Produto deletado com sucesso';

    protected $requestValidator = CarRequest::class;

    public function __construct(ProductService $service = null, Product $model = null)
    {
        parent::__construct($service, $model);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function get(){
        $model = Invoice::select('*');

        $response = DataTables::of($model)
            ->addColumn('actions', function ($model){
                return getBtnAction([
                    ['type'=>'edit',    'url' => routeTenant('cars.edit',['id' => $model->id])],
                    ['type'=>'other-a', 'url' => routeTenant('car_supply.index',['car_id' => $model->id]), 'name' => 'Abastencimentos'],
                    ['type'=>'delete',  'url' => routeTenant('cars.destroy',['id' => $model->id]), 'id' => $model->id]
                ]);
            })
            ->rawColumns(['actions'])
            ->toJson();
        return $response->original;
    }
}
