<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Services\BrandService;
use Yajra\DataTables\DataTables;
use Exception;

class BrandController extends CrudController
{
    protected $msgStore = 'Marca incluida com sucesso';

    protected $msgUpdate = 'Marca atualizada com sucesso';

    protected $msgDestroy = 'Marca deletada com sucesso';

    protected $requestValidator = BrandRequest::class;

    public function __construct(BrandService $service = null, Brand $model = null)
    {
        parent::__construct($service, $model);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function get(){
        $model = Brand::select();

        $response = DataTables::of($model)
            ->addColumn('actions', function ($model){
                return getBtnAction([
                    ['type'=>'edit', 'url' => routeTenant('brand.edit',[$model->id])],
                    ['type'=>'delete', 'url' => routeTenant('brand.destroy',[$model->id]), 'id' => $model->id]
                ]);
            })
            ->rawColumns(['actions'])
            ->toJson();
        return $response->original;
    }
}
