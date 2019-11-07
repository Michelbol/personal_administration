@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Adicionar Abastecimento</h3>

        <form action="{{ routeTenant('car_supply.store') }}" method="POST">
            {{ csrf_field() }}
            @include('car.supply._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Salvar</button>
                <a href="{{ routeTenant('car_supply.index', ['car_id' => $car_id]) }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
