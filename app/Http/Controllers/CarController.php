<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Models\CarSupply;
use App\Services\CarService;
use Carbon\Carbon;
use App\Models\Car;
use App\Utilitarios;
use Illuminate\Http\Request;
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

    public function get(){
        try{
            $model = Car::select(['id', 'model', 'license_plate', 'annual_licensing', 'annual_insurance']);

            $response = DataTables::of($model)
                ->addColumn('actions', function ($model){
                    return Utilitarios::getBtnAction([
                        ['type'=>'edit',    'url' => routeTenant('cars.edit',['id' => $model->id])],
                        ['type'=>'other-a', 'url' => routeTenant('car_supply.index',['car_id' => $model->id]), 'name' => 'Abastencimentos'],
                        ['type'=>'delete',  'url' => routeTenant('cars.destroy',['id' => $model->id]), 'id' => $model->id]
                    ]);
                })
                ->rawColumns(['actions'])
                ->toJson();
            return $response->original;
        }catch (\Exception $e){
            return response()->json(['error' =>'Error into datatable: '.$e->getMessage(), 'trace' => $e->getTrace()]);
        }
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
