@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Adicionar Marca</h3>

        <form action="{{ routeTenant('brand.store') }}" method="POST">
            {{ csrf_field() }}
            @include('brand._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Salvar</button>
                <a href="{{ routeTenant('brand.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
