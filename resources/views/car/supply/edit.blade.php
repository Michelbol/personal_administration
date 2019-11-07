@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Editar Abastecimento</h3>

        <form action="{{ routeTenant('car_supply.update', ['id' => $carSupply->id]) }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            @include('car.supply._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Atualizar</button>
                <a href="{{ routeTenant('car_supply.index', ['car_id' => $car_id]) }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
