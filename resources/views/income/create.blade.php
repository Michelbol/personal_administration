@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Adicionar Receita</h3>

        <form action="{{ routeTenant('income.store') }}" method="POST">
            {{ csrf_field() }}
            @include('income._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Salvar</button>
                <a href="{{ routeTenant('income.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
