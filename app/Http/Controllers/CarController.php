<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Models\Car;
use App\Models\CarSupply;
use App\Models\FipeHistory;
use App\Services\CarService;
use App\Services\FipeService;
use App\Utilitarios;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class CarController extends CrudController
{
    protected $msgStore = 'Carro incluido com sucesso';

    protected $msgUpdate = 'Carro atualizado com sucesso';

    protected $msgDestroy = 'Carro deletado com sucesso';

    protected $requestValidator = CarRequest::class;

    public function __construct(CarService $service = null, Car $model = null)
    {
        parent::__construct($service, $model);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $tenant
     * @return Factory|View
     */
    public function create($tenant)
    {
        $brands = (new FipeService())->getBrands();
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
        $brands = (new FipeService())->getBrands();
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
        $year = $request->get('year', Carbon::now()->year);
        $liters = CarSupply::calcMonthlyLiters($year, $id);
        $totalPaid = CarSupply::calcMonthlyTotalPaid($year, $id);
        $traveledKilometers = CarSupply::calcMonthlyTraveledKilometers($year, $id);
        $averages = [];
        foreach ($liters as $index => $liter){
            $averages[$index] = 0;
            if($liter > 0){
                $averages[$index] = $traveledKilometers[$index] / $liter;
            }
        }
        $liters = collect($liters)->implode(',');
        $averages = collect($averages)->implode(',');
        $totalPaid = collect($totalPaid)->implode(',');
        $traveledKilometers = collect($traveledKilometers)->implode(',');

        return view('car.profile', compact('totalPaid', 'liters', 'traveledKilometers', 'averages', 'year', 'id'));
    }
}
