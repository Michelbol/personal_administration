<?php

namespace App\Http\Controllers;

use \Session;
use \Exception;
use App\Models\Car;
use App\Utilitarios;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CarController extends Controller
{
    public function index(){
        return view('car.index');
    }

    public function create(){
        return view('car.create');
    }

    public function store(Request $request){

        $validData = $request->validate([
            'model' => 'max:100',
            'license_plate' => 'required|max:15',
            'annual_licensing' => 'date_format:d/m/Y',
            'annual_insurance' => 'date_format:d/m/Y',
        ]);

        $car = new Car();
        $car->model = $validData['model'];
        $car->license_plate = $validData['license_plate'];
        $car->annual_licensing = $validData['annual_licensing'];
        $car->annual_insurance = $validData['annual_insurance'];
        $car->save();
        Session::flash('message', ['msg' => 'Carro incluido com sucesso', 'type' => 'success']);
        return redirect()->routeTenant('car.index');
    }

    public function edit($tenant, $id){
        $car = Car::findOrFail($id);
        return view('car.edit', compact('car'));
    }

    public function update(Request $request, $tenant, $id){
        $validData = $request->validate([
            'id' => 'required',
            'model' => 'max:100',
            'license_plate' => 'required|max:15',
            'annual_licensing' => 'date_format:d/m/Y',
            'annual_insurance' => 'date_format:d/m/Y',
        ]);

        $car = Car::find($id);
        $car->model = $validData['model'];
        $car->license_plate = $validData['license_plate'];
        $car->annual_licensing = $validData['annual_licensing'];
        $car->annual_insurance = $validData['annual_insurance'];
        $car->save();
        Session::flash('message', ['msg' => 'Carro atualizado com sucesso', 'type' => 'success']);
        return redirect()->routeTenant('car.index');
    }

    public function destroy($tenant, $id){
        try{
            $car = Car::findOrFail($id);
            $car->delete();
            Session::flash('message', ['msg' => 'Carro deletado com sucesso', 'type' => 'success']);
            return redirect()->routeTenant('car.index');
        }catch (Exception $e){
            Session::flash('message', ['msg' => 'Erro ao deletar carro:'.$e->getMessage(), 'type' => 'success']);
            return redirect()->routeTenant('car.index');
        }
    }

    public function get(){
        try{
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
        }catch (\Exception $e){
            dd('erro!'.$e->getMessage());
        }
    }

    public function profile($tenant, $id){
        $carSupply = collect([1,2,3,4,5,6,7,8,9,10,11,12]);
        return view('car.profile', compact('carSupply'));
    }
}
