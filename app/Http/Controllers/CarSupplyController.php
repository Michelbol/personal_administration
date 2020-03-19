<?php

namespace App\Http\Controllers;

use App\Models\Enum\FuelEnum;
use \Exception;
use App\Models\Car;
use App\Utilitarios;
use App\Models\CarSupply;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;

class CarSupplyController extends Controller
{
    public function index($tenant, $car_id){
        $car = Car::findOrFail($car_id, ['id', 'license_plate']);
        return view('car.supply.index', compact('car'));
    }

    public function create($tenant, $car_id){
        return view('car.supply.create', compact('car_id'));
    }

    public function store(Request $request){
        $validData = $request->validate([
            'car_id' => 'required',
            'date' => 'date_format:d/m/Y',
            'gas_station' => 'max:150'
        ]);
        $carSupply = new CarSupply();
        $carSupply->car_id        = $validData['car_id'];
        $carSupply->kilometer     = $request->get('kilometer', null);
        $carSupply->liters        = $request->get('liters', null);
        $carSupply->total_paid    = $request->get('total_paid', null);
        $carSupply->date          = $validData['date'];
        $carSupply->fuel          = $request->get('fuel', FuelEnum::Gasolina);
        $carSupply->gas_station   = $validData['gas_station'];
        $carSupply->calcTraveledKilometer();
        $carSupply->save();
        Session::flash('message', ['msg' => 'Abastecimento incluido com sucesso', 'type' => 'success']);
        return redirect()->routeTenant('car_supply.index',['car_id' => $carSupply->car_id]);
    }

    public function edit($tenant, $id){
        $carSupply = CarSupply::findOrFail($id);
        $car_id = $carSupply->car_id;
        return view('car.supply.edit', compact('carSupply', 'car_id'));
    }

    public function update(Request $request, $tenant, $id){
        $validData = $request->validate([
            'car_id' => 'required',
            'date' => 'date_format:d/m/Y',
            'gas_station' => 'max:150'
        ]);
        $carSupply = CarSupply::findOrFail($id);
        $carSupply->car_id        = $validData['car_id'];
        $carSupply->kilometer     = $request->get('kilometer', null);
        $carSupply->liters        = $request->get('liters', null);
        $carSupply->total_paid    = $request->get('total_paid', null);
        $carSupply->date          = $validData['date'];
        $carSupply->fuel          = $request->get('fuel', FuelEnum::Gasolina);
        $carSupply->gas_station   = $validData['gas_station'];
        $carSupply->calcTraveledKilometer();
        $carSupply->save();
        Session::flash('message', ['msg' => 'Abastecimento Atualizado com sucesso', 'type' => 'success']);
        return redirect()->routeTenant('car_supply.index',['car_id' => $carSupply->car_id]);
    }

    public function destroy($tenant, $id){
        try{
            $carSupply = CarSupply::findOrFail($id, ['id', 'car_id']);
            $carSupply->delete();
            Session::flash('message', ['msg' => 'Abastecimento deletado com sucesso', 'type' => 'success']);
            return redirect()->routeTenant('car_supply.index',['car_id' => $carSupply->car_id]);
        }catch (Exception $e){
            Session::flash('message', ['msg' => 'Erro ao deletar Abastecimento:'.$e->getMessage(), 'type' => 'success']);
            return redirect()->routeTenant('cars.index');
        }
    }

    public function get($tenant, $id){
        try{
            $model = CarSupply::select(['id', 'kilometer', 'liters', 'total_paid', 'date', 'fuel', 'gas_station'])
                ->where('car_id', $id)
                ->orderByDesc('date');

            $response = DataTables::of($model)
                ->addColumn('actions', function ($model){
                    return Utilitarios::getBtnAction([
                        ['type'=>'edit',    'url' => routeTenant('car_supply.edit',['id' => $model->id])],
                        ['type'=>'delete',  'url' => routeTenant('car_supply.destroy',['id' => $model->id]), 'id' => $model->id]
                    ]);
                })
                ->rawColumns(['actions'])
                ->toJson();
            return $response->original;
        }catch (\Exception $e){
            dd('erro!'.$e->getMessage());
        }
    }
}
