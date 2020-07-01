<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use App\Services\SupplierService;
use Exception;
use Yajra\DataTables\DataTables;

class SupplierController extends CrudController
{
    protected $msgStore = 'Fornecedor incluido com sucesso';

    protected $msgUpdate = 'Fornecedor atualizado com sucesso';

    protected $msgDestroy = 'Fornecedor deletado com sucesso';

    protected $requestValidator = SupplierRequest::class;

    public function __construct(SupplierService $service = null, Supplier $model = null)
    {
        parent::__construct($service, $model);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function get(){
        $model = Supplier::select('*');

        $response = DataTables::of($model)
            ->addColumn('actions', function ($model){
                return getBtnAction([
                    ['type'=>'edit',    'url' => routeTenant('supplier.edit',[$model->id])],
                    ['type'=>'delete',  'url' => routeTenant('supplier.destroy',[$model->id]), 'id' => $model->id]
                ]);
            })
            ->rawColumns(['actions'])
            ->toJson();
        return $response->original;
    }
}
