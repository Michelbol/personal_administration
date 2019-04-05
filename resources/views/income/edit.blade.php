@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Editar Receita</h3>

        <form action="{{ routeTenant('income.update', ['id' => $income->id]) }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            @include('income._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Atualizar</button>
                <a href="{{ routeTenant('income.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
