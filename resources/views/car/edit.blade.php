@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Editar Carro</h3>

        <form action="{{ routeTenant('cars.update', ['id' => $car->id]) }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            @include('car._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Atualizar</button>
                <a href="{{ routeTenant('cars.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
