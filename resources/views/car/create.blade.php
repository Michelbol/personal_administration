@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Adicionar Carro</h3>

        <form action="{{ routeTenant('car.store') }}" method="POST">
            {{ csrf_field() }}
            @include('car._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Salvar</button>
                <a href="{{ routeTenant('car.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
