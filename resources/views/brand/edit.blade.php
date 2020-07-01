@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Editar Marca</h3>

        <form action="{{ routeTenant('brand.update', ['brand' => $brand->id]) }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            @include('brand._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Atualizar</button>
                <a href="{{ routeTenant('brand.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
