<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Models\Car;
use App\Models\CarSupply;
use App\Models\FipeHistory;
use App\Services\CarService;
use App\Services\FipeService;
use App\Utilitarios;
use Illuminate\Support\Carbon;
use DB;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class CarController extends CrudController
{
    protected $msgStore = 'Carro incluido com sucesso';
    /**
     * @var string
     */
    protected $msgUpdate = 'Carro atualizado com sucesso';
    /**
     * @var string
     */
    protected $msgDestroy = 'Carro deletado com sucesso';
    /**
     * @var string
     */
    protected $requestValidator = CarRequest::class;
    /**
     * @var FipeService
     */
    private $fipeService;

    public function __construct(FipeService $fipeService, CarService $service = null, Car $model = null)
    {
        parent::__construct($service, $model);
        $this->fipeService = $fipeService;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $tenant
     * @return Factory|View
     */
    public function create($tenant)
    {
        $brands = $this->fipeService->getBrands();
        $data = [
            'brands' => $brands
        ];
        return view("car.create", $data);
    }

    /**
     * @param $tenant
     * @param $id
     * @param Request $request
     * @return Factory|View
     */
    public function edit($tenant, $id, Request $request){
        $car = Car::findOrFail($id);
        try {
            $brands = $this->fipeService->getBrands();
        }catch (Exception $exception){
            $brands = [];
        }
        $histories = FipeHistory
            ::whereCarId($car->id)
            ->orderBy('consultation_date')
            ->select(
                DB::raw('DATE_FORMAT(consultation_date, "%d/%m/%Y") as format_consultation_date'),
                'value'
            )
            ->get();
        $histories = [
            'dates' => $histories->pluck('format_consultation_date'),
            'values' => $histories->pluck('value'),
        ];
        return view("car.edit", compact('car', 'brands', 'histories'));
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function get(){
        $model = Car::select(['id', 'model', 'license_plate', 'annual_licensing', 'annual_insurance']);

        $response = DataTables::of($model)
            ->addColumn('actions', function ($model){
                return Utilitarios::getBtnAction([
                    ['type'=>'edit',    'url' => routeTenant('car.edit',['id' => $model->id])],
                    ['type'=>'other-a', 'url' => routeTenant('car_supply.index',['car_id' => $model->id]), 'name' => 'Abastencimentos'],
                    ['type'=>'delete',  'url' => routeTenant('car.destroy',['id' => $model->id]), 'id' => $model->id]
                ]);
            })
            ->rawColumns(['actions'])
            ->toJson();
        return $response->original;
    }

    public function profile($tenant, $id, Request $request){
        $startAt = Carbon::now()->startOfYear();
        $endAt = Carbon::now();
        if($request->has('period') && $request->has('period')){
            $date = explode(' - ', $request->get('period'));
            $startAt = Carbon::createFromFormat('d/m/Y', $date[0]);
            $endAt = Carbon::createFromFormat('d/m/Y', $date[1]);
        }
        $values = CarSupply::calcMonthlyValues($startAt, $endAt, $id);
        $globalMedia = CarSupply::calcGlobalMedia($id)->average;
        $liters = $values->pluck('liters')->implode(',');
        $averages = $values->pluck('average')->implode(',');
        $totalPaid = $values->pluck('total_paid')->implode(',');
        $traveledKilometers = $values->pluck('traveled_kilometers')->implode(',');
        $endAt = $endAt->format('d/m/Y');
        $startAt = $startAt->format('d/m/Y');
        return view(
            'car.profile',
            compact(
                'totalPaid',
                'liters',
                'traveledKilometers',
                'averages',
                'id',
                'startAt',
                'endAt',
                'globalMedia'
            ));
    }
}
